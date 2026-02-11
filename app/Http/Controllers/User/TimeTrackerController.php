<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JobName;
use App\Models\ProjectName;
use App\Models\TimeTracker;
use Illuminate\Http\Request;
use App\Http\Requests\User\TimeTrackerRequest;
use App\Mail\ProjectStartDateNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;
class TimeTrackerController extends Controller
{
    private $projectStartDateDepartments = [62, 68, 70, 71,73, 85,86];

    public function add_time_tracker_info()
    {
        $project_names = ProjectName::where('user_id', auth()->user()->id)
        ->orderBy('created_at', 'desc')
        ->get();
        $job_names = JobName::where('user_id', auth()->user()->id)
        ->orderBy('created_at', 'desc')
        ->get();
        $showProjectStartDate = $this->shouldShowProjectStartDate();
        return view('User.time_tracker.add', compact('project_names', 'job_names', 'showProjectStartDate'));
    }

    public function getProjectStartDate($id)
    {
        $timeTracker = \App\Models\TimeTracker::where('project_id', $id)
            ->whereNotNull('project_start_date')
            ->orderBy('created_at', 'desc')
            ->first();

        $is_overdue = false;
        if ($timeTracker && $timeTracker->project_start_date) {
            $user = auth()->user();
            
            // Priority: Check new_deadline if it exists
            if ($timeTracker->new_deadline) {
                $deadline = Carbon::parse($timeTracker->new_deadline);
                $is_overdue = Carbon::now()->greaterThan($deadline);
            } else {
                // Fallback: Check original start date + threshold
                $thresholdMonths = ((int) $user->department_id === 86) ? 2 : 4;
                $startDate = Carbon::parse($timeTracker->project_start_date);
                $deadline = $startDate->copy()->addMonths($thresholdMonths);
                $is_overdue = Carbon::now()->greaterThan($deadline);
            }
        }

        return response()->json([
            'exists' => $timeTracker ? true : false,
            'start_date' => $timeTracker->project_start_date ?? null,
            'is_overdue' => $is_overdue,
        ]);
    }



    private function shouldShowProjectStartDate()
    {
        $user = auth()->user();

        return in_array($user->department_id, $this->projectStartDateDepartments);
    }
public function create_time_tracker_info(TimeTrackerRequest $request)
{
    $time_tracker_info = new TimeTracker();
    $time_tracker_info->user_id = auth()->user()->id;
    $time_tracker_info->project_id = $request->project_name;
    $time_tracker_info->job_id = $request->job_name;
    $time_tracker_info->work_date = $request->date;
    $time_tracker_info->work_title = $request->work_description;
    $time_tracker_info->work_time = $request->hours;

    $user = auth()->user();
    $shouldShowProjectDate = in_array($user->department_id, $this->projectStartDateDepartments);

    // Add project type if provided
    if ($shouldShowProjectDate && $request->has('project_type')) {
        $time_tracker_info->project_type = $request->project_type;
    }

    if ($shouldShowProjectDate) {
        $projectType = $request->project_type;

        // Check if project type is marketing, support, or meeting
        if (in_array($projectType, ['marketing', 'support', 'meeting'])) {
            // For these project types, don't require BA email or project start date
            $time_tracker_info->project_start_date = null;
            $time_tracker_info->ba_notified = false;
            $time_tracker_info->ba_filled = false;
            $time_tracker_info->ba_email = null;
        } else if ($projectType === 'development') {
            // For development projects, check if project already has a start date
            $existingProject = TimeTracker::where('project_id', $request->project_name)
                ->whereNotNull('project_start_date')
                ->first();

            if ($existingProject) {
                // Project already has start date, use existing data
                $time_tracker_info->project_start_date = $existingProject->project_start_date;
                $time_tracker_info->ba_notified = true;
                $time_tracker_info->ba_filled = true;
                $time_tracker_info->ba_email = $existingProject->ba_email;
            } else {
                // New development project, require BA email and will send notification
                $time_tracker_info->project_start_date = null;
                $time_tracker_info->ba_notified = false;
                $time_tracker_info->ba_filled = false;
                $time_tracker_info->ba_email = $request->ba_email;
            }
        } else {
            // Default case (if no project type selected for development departments)
            $existingProject = TimeTracker::where('project_id', $request->project_name)
                ->whereNotNull('project_start_date')
                ->first();

            if ($existingProject) {
                $time_tracker_info->project_start_date = $existingProject->project_start_date;
                $time_tracker_info->ba_notified = true;
                $time_tracker_info->ba_filled = true;
                $time_tracker_info->ba_email = $existingProject->ba_email;
            } else {
                $time_tracker_info->project_start_date = null;
                $time_tracker_info->ba_notified = false;
                $time_tracker_info->ba_filled = false;
            }
        }
    } else {
        // For departments NOT in $projectStartDateDepartments
        $time_tracker_info->project_start_date = null;
        $time_tracker_info->ba_notified = false;
        $time_tracker_info->ba_filled = false;
    }

    $time_tracker_info->save();

    // Save project status and reason if provided (for overdue projects)
    if ($request->has('project_status')) {
        $time_tracker_info->project_status = $request->project_status;
        $time_tracker_info->status_reason = $request->status_reason;
        
        // --- PERSISTENT APPROVAL LOGIC ---
        // Check if there is an existing approved/rejected decision for this project
        $prevDecision = TimeTracker::where('project_id', $request->project_name)
            ->where('user_id', auth()->user()->id)
            ->whereNotNull('hr_status')
            ->orderBy('created_at', 'desc')
            ->first();

        if ($prevDecision) {
            $isOverdueAgainstNewDeadline = false;
            if ($prevDecision->new_deadline) {
                $isOverdueAgainstNewDeadline = Carbon::now()->greaterThan(Carbon::parse($prevDecision->new_deadline));
            }

            // If a decision exists and we haven't breached the NEW deadline yet, inherit the status
            if (!$isOverdueAgainstNewDeadline) {
                $time_tracker_info->hr_status = $prevDecision->hr_status;
                $time_tracker_info->new_deadline = $prevDecision->new_deadline;
                $time_tracker_info->ba_delay_reason = $prevDecision->ba_delay_reason;
            } else {
                // If we breached the new deadline, reset to pending for a new review cycle
                $time_tracker_info->hr_status = 'pending';
            }
        } else {
            // First time overdue, set to pending
            $time_tracker_info->hr_status = 'pending';
        }
        // ---------------------------------

        $time_tracker_info->save();
    }

    // Send email to BA only if needed
    $needsNotification = $shouldShowProjectDate
        && $projectType === 'development'
        && !TimeTracker::where('project_id', $request->project_name)->whereNotNull('project_start_date')->exists()
        && $request->has('ba_email')
        && $request->ba_email;

    if ($needsNotification) {
        $this->sendBaNotification($time_tracker_info, $request->ba_email);

        $time_tracker_info->ba_notified = true;
        $time_tracker_info->save();
    }

    return redirect()->route('view_time_tracker_info', ['start_date' => 0, 'end_date' => 0])
        ->with('success', 'Time tracker added successfully!');
}




    private function sendBaNotification(TimeTracker $timeTracker, string $baEmail)
    {
        try {
            Mail::to($baEmail)->send(new ProjectStartDateNotification($timeTracker));
        } catch (\Exception $e) {
            Log::error('Failed to send BA notification: ' . $e->getMessage());
        }
    }

    // public function updateProjectStartDate(Request $request, $id)
    // {
    //     $request->validate([
    //         'project_start_date' => 'required|date'
    //     ]);

    //     $timeTracker = TimeTracker::findOrFail($id);

    //     // Only allow BA to update this field
    //     // You might want to add proper authorization here
    //     $timeTracker->project_start_date = $request->project_start_date;
    //     $timeTracker->ba_filled = true;
    //     $timeTracker->save();

    //     return response()->json(['success' => true, 'message' => 'Project start date updated successfully']);
    // }



    public function view_time_tracker_info($start_date, $end_date)
    {
        if ($start_date == 0) {
            $today = date('Y-m-d');
            $start_date = date('Y-m-01', strtotime($today));
        }
        if ($end_date == 0) {
            $end_date = date('Y-m-d');
        }
        $raw_time_trackers = TimeTracker::with(['project', 'job'])
            ->where('user_id', auth()->user()->id)
            ->whereDate('work_date', '>=', $start_date)
            ->whereDate('work_date', '<=', $end_date)
            ->orderBy('work_date', 'desc')
            ->get();
        $time_trackers = array();
        foreach ($raw_time_trackers as $raw_time_tracker) {
            $time_trackers[$raw_time_tracker->work_date][] = $raw_time_tracker;
        }
        $user = auth()->user();
        $shouldShowProjectDate = in_array($user->department_id, $this->projectStartDateDepartments);
        return view('User.time_tracker.view', compact('time_trackers', 'start_date', 'end_date', 'shouldShowProjectDate'));
    }

    public function add_project_name(Request $request)
    {
        $project_name = new ProjectName();
        $project_name->name = $request->project_name;
        $project_name->user_id = auth()->id();
        $project_name->save();
        $response = [
            'success' => true,
            'message' => 'Project Name added successfully',
            'project_name' => $project_name,
        ];
        // Return the response as JSON
        return response()->json($response);
    }

    public function add_job_name(Request $request)
    {
        $job_name = new JobName();
        $job_name->name = $request->job_name;
        $job_name->user_id = auth()->id();
        $job_name->save();
        $response = [
            'success' => true,
            'message' => 'Job Name added successfully',
            'job_name' => $job_name,
        ];

        // Return the response as JSON
        return response()->json($response);
    }

   public function edit_time_tracker_info(Request $request)
{
    $project_names = ProjectName::where('user_id', auth()->user()->id)->get();
    $job_names = JobName::where('user_id', auth()->user()->id)->get();
    $user = auth()->user();
    $shouldShowProjectDate = in_array($user->department_id, $this->projectStartDateDepartments);
    $time_tracker_info = TimeTracker::find($request->id);

    return view('User.time_tracker.edit', compact('time_tracker_info', 'project_names', 'job_names', 'shouldShowProjectDate'));
}

public function update_time_tracker_info(TimeTrackerRequest $request)
{
    $time_tracker_info = TimeTracker::find($request->time_tracker_info);
    $time_tracker_info->user_id = auth()->user()->id;
    $time_tracker_info->project_id = $request->project_name;
    $time_tracker_info->job_id = $request->job_name;
    $time_tracker_info->work_date = $request->date;
    $time_tracker_info->work_title = $request->work_description;
    $time_tracker_info->work_time = $request->hours;

    $user = auth()->user();
    $shouldShowProjectDate = in_array($user->department_id, $this->projectStartDateDepartments);

    // Add project type if provided
    if ($shouldShowProjectDate && $request->has('project_type')) {
        $time_tracker_info->project_type = $request->project_type;
    }

    if ($shouldShowProjectDate) {
        $projectType = $request->project_type;

        // Check if project type is marketing, support, or meeting
        if (in_array($projectType, ['marketing', 'support', 'meeting'])) {
            // For these project types, don't require BA email or project start date
            $time_tracker_info->project_start_date = null;
            $time_tracker_info->ba_notified = false;
            $time_tracker_info->ba_filled = false;
            $time_tracker_info->ba_email = null;
        } else if ($projectType === 'development') {
            // For development projects, check if project already has a start date
            $existingProject = TimeTracker::where('project_id', $request->project_name)
                ->whereNotNull('project_start_date')
                ->where('id', '!=', $request->time_tracker_info) // Exclude current record
                ->first();

            if ($existingProject) {
                // Project already has start date, use existing data
                $time_tracker_info->project_start_date = $existingProject->project_start_date;
                $time_tracker_info->ba_notified = true;
                $time_tracker_info->ba_filled = true;
                $time_tracker_info->ba_email = $existingProject->ba_email;
            } else {
                // Check if current record has project start date or needs BA email
                if ($request->has('ba_email') && $request->ba_email) {
                    $time_tracker_info->ba_email = $request->ba_email;
                    $time_tracker_info->ba_notified = false;
                    $time_tracker_info->ba_filled = false;
                } else if ($time_tracker_info->project_start_date) {
                    // Keep existing project start date if already set
                    $time_tracker_info->ba_filled = true;
                }

                // Update project start date if provided
                if ($request->has('project_start_date') && $request->project_start_date) {
                    $time_tracker_info->project_start_date = $request->project_start_date;
                    $time_tracker_info->ba_filled = true;
                }
            }
        } else {
            // Default case (if no project type selected for development departments)
            $existingProject = TimeTracker::where('project_id', $request->project_name)
                ->whereNotNull('project_start_date')
                ->where('id', '!=', $request->time_tracker_info) // Exclude current record
                ->first();

            if ($existingProject) {
                $time_tracker_info->project_start_date = $existingProject->project_start_date;
                $time_tracker_info->ba_notified = true;
                $time_tracker_info->ba_filled = true;
                $time_tracker_info->ba_email = $existingProject->ba_email;
            } else {
                // Keep existing values or set defaults
                if (!$time_tracker_info->project_start_date) {
                    $time_tracker_info->project_start_date = null;
                    $time_tracker_info->ba_notified = false;
                    $time_tracker_info->ba_filled = false;
                }
            }
        }
    } else {
        // For departments NOT in $projectStartDateDepartments
        $time_tracker_info->project_start_date = null;
        $time_tracker_info->ba_notified = false;
        $time_tracker_info->ba_filled = false;
    }

    $time_tracker_info->save();

    // Save project status and reason if provided (for overdue projects)
    if ($request->has('project_status')) {
        $time_tracker_info->project_status = $request->project_status;
        $time_tracker_info->status_reason = $request->status_reason;

        // --- PERSISTENT APPROVAL LOGIC ---
        // Check for an existing decision for this project (excluding current if updating)
        $prevDecision = TimeTracker::where('project_id', $request->project_name)
            ->where('user_id', auth()->user()->id)
            ->whereNotNull('hr_status')
            ->where('id', '!=', $time_tracker_info->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($prevDecision) {
            $isOverdueAgainstNewDeadline = false;
            if ($prevDecision->new_deadline) {
                $isOverdueAgainstNewDeadline = Carbon::now()->greaterThan(Carbon::parse($prevDecision->new_deadline));
            }

            // Inherit decision if still within the secondary deadline
            if (!$isOverdueAgainstNewDeadline) {
                $time_tracker_info->hr_status = $prevDecision->hr_status;
                $time_tracker_info->new_deadline = $prevDecision->new_deadline;
                $time_tracker_info->ba_delay_reason = $prevDecision->ba_delay_reason;
            } else {
                // Second breach detected, needs new HR review
                $time_tracker_info->hr_status = 'pending';
            }
        } else {
            // No previous decision, ensure it's pending if not already processed
            if (!$time_tracker_info->hr_status) {
                $time_tracker_info->hr_status = 'pending';
            }
        }
        // ---------------------------------

        $time_tracker_info->save();
    }

    // Send email to BA only if needed (for new development projects or when BA email is updated)
    $needsNotification = $shouldShowProjectDate
        && $request->project_type === 'development'
        && $request->has('ba_email')
        && $request->ba_email
        && (!$time_tracker_info->ba_notified || $time_tracker_info->ba_email !== $request->ba_email)
        && !TimeTracker::where('project_id', $request->project_name)
                      ->whereNotNull('project_start_date')
                      ->where('id', '!=', $request->time_tracker_info)
                      ->exists();

    if ($needsNotification) {
        $this->sendBaNotification($time_tracker_info, $request->ba_email);

        $time_tracker_info->ba_notified = true;
        $time_tracker_info->save();
    }

    return redirect()->route('view_time_tracker_info', ['start_date' => 0, 'end_date' => 0])
        ->with('success', 'Time tracker updated successfully!');
}
     public function showUpdateForm($id)
    {
        $timeTracker = TimeTracker::findOrFail($id);
        return view('User.time_tracker.update-project-date', compact('timeTracker'));
    }

    public function updateProjectStartDate(Request $request, $id)
    {
        $request->validate([
            'project_start_date' => 'required|date',
        ]);

        $timeTracker = TimeTracker::findOrFail($id);
        
        // Update all entries for this project that don't have a start date
        TimeTracker::where('project_id', $timeTracker->project_id)
            ->whereNull('project_start_date')
            ->update([
                'project_start_date' => $request->project_start_date,
                'ba_filled' => true
            ]);

        return redirect()->back()->with('success', 'Project start date updated successfully.');
    }

    public function showNewDeadlineForm($id)
    {
        $timeTracker = TimeTracker::findOrFail($id);
        return view('User.time_tracker.update-new-deadline', compact('timeTracker'));
    }

    public function updateNewDeadline(Request $request, $id)
    {
        $request->validate([
            'new_deadline' => 'required|date',
            'ba_delay_reason' => 'required|string',
        ]);

        $timeTracker = TimeTracker::findOrFail($id);
        $timeTracker->new_deadline = $request->new_deadline;
        $timeTracker->ba_delay_reason = $request->ba_delay_reason;
        $timeTracker->save();

        return redirect()->back()->with('success', 'New deadline and delay reason updated successfully.');
    }

    public function DeleteTimeTracker($id){
        $time_tracker_info = TimeTracker::find($id);
        $time_tracker_info->delete();
        return redirect()->route('view_time_tracker_info', ['start_date' => 0, 'end_date' => 0])
            ->with('success', 'Time tracker deleted successfully!');
    }
}

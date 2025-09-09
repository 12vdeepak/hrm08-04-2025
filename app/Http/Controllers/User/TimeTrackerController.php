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
class TimeTrackerController extends Controller
{
    private $projectStartDateDepartments = [62, 68, 70, 71,73, 85];

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

    // Check if user's department requires project start date
    $user = auth()->user();
    $shouldShowProjectDate = in_array($user->department_id, $this->projectStartDateDepartments);

    if ($shouldShowProjectDate) {
        // 🔍 Check if any time tracker for this project already has a start date filled
        $existingProject = TimeTracker::where('project_id', $request->project_name)
            ->whereNotNull('project_start_date')
            ->first();

        if ($existingProject) {
            // ✅ Reuse the existing project start date
            $time_tracker_info->project_start_date = $existingProject->project_start_date;
            $time_tracker_info->ba_notified = true;
            $time_tracker_info->ba_filled = true;
            $time_tracker_info->ba_email = $existingProject->ba_email; // optional
        } else {
            // 🚨 No start date yet, BA still needs to fill it
            $time_tracker_info->project_start_date = null;
            $time_tracker_info->ba_notified = false;
            $time_tracker_info->ba_filled = false;
        }
    }

    $time_tracker_info->save();

    // Send email to BA only if needed
    if ($shouldShowProjectDate && !$existingProject && $request->has('ba_email') && $request->ba_email) {
        $this->sendBaNotification($time_tracker_info, $request->ba_email);

        // Mark BA as notified
        $time_tracker_info->ba_notified = true;
        $time_tracker_info->ba_email = $request->ba_email;
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

    public function showUpdateForm(TimeTracker $timeTracker)
{
    // Optional: block edits if already set
    if ($timeTracker->project_start_date) {
        return view('emails.ba.update-project-date', [
            'timeTracker' => $timeTracker,
            'alreadySet'  => true
        ]);
    }

    return view('emails.ba.update-project-date', [
        'timeTracker' => $timeTracker,
        'alreadySet'  => false
    ]);
}

public function updateProjectStartDate(Request $request, TimeTracker $timeTracker)
{
    $validated = $request->validate([
        'project_start_date' => ['required', 'date'],
    ]);

    // Prevent double submit
    if ($timeTracker->project_start_date) {
        return back()->with('info', 'Project start date is already set.');
    }

    $timeTracker->project_start_date = $validated['project_start_date'];
    $timeTracker->ba_filled = true; // requires column from Step 0
    $timeTracker->save();

    return redirect()
            ->route('ba.update.project.date.form', $timeTracker)
        ->with('success', 'Project start date updated successfully.');
}

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

    public function edit_time_tracker_info(Request $request){
        $project_names = ProjectName::where('user_id', auth()->user()->id)->get();
        $job_names = JobName::where('user_id', auth()->user()->id)->get();
        $user = auth()->user();
        $shouldShowProjectDate = in_array($user->department_id, $this->projectStartDateDepartments);
        $time_tracker_info = TimeTracker::find($request->id);
        return view('User.time_tracker.edit', compact('time_tracker_info','project_names', 'job_names', 'shouldShowProjectDate'));
    }

    public function update_time_tracker_info(TimeTrackerRequest $request){
        $resultTime=0;


        $time_tracker_info = TimeTracker::find($request->time_tracker_info);
        $time_tracker_info->user_id = auth()->user()->id;
        $time_tracker_info->project_id = $request->project_name;
        $time_tracker_info->job_id = $request->job_name;
        $time_tracker_info->work_date = $request->date;
        $time_tracker_info->work_title = $request->work_description;
        $time_tracker_info->work_time = $request->hours;
        $user = auth()->user();
            $shouldShowProjectDate = in_array($user->department_id, $this->projectStartDateDepartments);
        if ($shouldShowProjectDate) {
            $time_tracker_info->project_start_date = $request->project_start_date;
            $time_tracker_info->ba_filled = true;
        }
        $time_tracker_info->save();
        return redirect()->route('view_time_tracker_info', ['start_date' => 0, 'end_date' => 0])
            ->with('success', 'Time tracker updated successfully!');
    }
     public function DeleteTimeTracker($id){
        $time_tracker_info = TimeTracker::find($id);
        $time_tracker_info->delete();
        return redirect()->route('view_time_tracker_info', ['start_date' => 0, 'end_date' => 0])
            ->with('success', 'Time tracker deleted successfully!');
    }
}

<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\JobName;
use App\Models\ProjectName;
use App\Models\TimeTracker;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class TimeTrackerController extends Controller
{
    public function add_time_tracker_info()
    {
        $project_names = ProjectName::where('user_id', auth()->user()->id)->get();
        $job_names = JobName::where('user_id', auth()->user()->id)->get();
        return view('User.time_tracker.add', compact('project_names', 'job_names'));
    }

    public function create_time_tracker_info(Request $request)
    {
     //dd($request->all());   
        $validator = Validator::make($request->all(), [
            'project_name' => 'required',
            'job_name' => 'required',
            'date' => 'required',
            'work_description' => 'required',
            'hours'=>['required','regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/']
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }

        //$startTime =  Carbon::createFromTimeString($request->start_time);
        //$endTime =  Carbon::createFromTimeString($request->end_time);
        //$differenceTime = $endTime->diff($startTime);
        //$resultTime = $differenceTime->format('%H:%I');

        $time_tracker_info = new TimeTracker();
        $time_tracker_info->user_id = auth()->user()->id;
        $time_tracker_info->project_id = $request->project_name;
        $time_tracker_info->job_id = $request->job_name;
        $time_tracker_info->work_date = $request->date;
        $time_tracker_info->work_title = $request->work_description;
        $time_tracker_info->work_time=$request->hours;
        //dd($time_tracker_info);
       // if($request->start_time){
       //     $time_tracker_info->start_time = $request->start_time;
       // }
       // if ($request->end_time) {
       //     $time_tracker_info->end_time = $request->end_time;
       // }
       // if (strtotime($resultTime) > strtotime($request->hours)) {
       //     $time_tracker_info->work_time = $resultTime;
       // } else {
       //     $time_tracker_info->work_time = $request->hours;
       // }
        $time_tracker_info->save();
        return redirect()->route('view_time_tracker_info', ['start_date' => 0, 'end_date' => 0]);
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
        $raw_time_trackers = TimeTracker::where('user_id', auth()->user()->id)->whereDate('work_date', '>=', $start_date)->whereDate('work_date', '<=', $end_date)->orderBy('work_date', 'desc')->get();
        $time_trackers = array();
        foreach ($raw_time_trackers as $raw_time_tracker) {
            $time_trackers[$raw_time_tracker->work_date][] = $raw_time_tracker;
        }
        return view('User.time_tracker.view', compact('time_trackers', 'start_date', 'end_date'));
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

        $time_tracker_info = TimeTracker::find($request->id);
        return view('User.time_tracker.edit', compact('time_tracker_info','project_names', 'job_names'));
    }

 public function update_time_tracker_info(Request $request){
        $validator = Validator::make($request->all(), [
            'project_name' => 'required',
            'job_name' => 'required',
            'date' => 'required',
            'work_description' => 'required',
            'hours'=>['required','regex:/^(?:2[0-3]|[01][0-9]):[0-5][0-9]$/']
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator)->withInput();
        }
        $resultTime=0;
       // if($request->start_time !=null && $request->end_time != null){
       //     $startTime =  Carbon::createFromTimeString($request->start_time);
       //     $endTime =  Carbon::createFromTimeString($request->end_time);
       //     $differenceTime = $endTime->diff($startTime);
       //     $resultTime = $differenceTime->format('%H:%I');
       // }

        $time_tracker_info = TimeTracker::find($request->time_tracker_info);
        $time_tracker_info->user_id = auth()->user()->id;
        $time_tracker_info->project_id = $request->project_name;
        $time_tracker_info->job_id = $request->job_name;
        $time_tracker_info->work_date = $request->date;
        $time_tracker_info->work_title = $request->work_description;
        $time_tracker_info->work_time = $request->hours;
        //if ($request->start_time) {
        //    $time_tracker_info->start_time = $request->start_time;
        //}
        //if ($request->end_time) {
        //    $time_tracker_info->end_time = $request->end_time;
        //}
        //if (strtotime($resultTime) > strtotime($request->hours)) {
        //    $time_tracker_info->work_time = $resultTime;
        //} else {
        //    $time_tracker_info->work_time = $request->hours;
        //}
        $time_tracker_info->save();
        return redirect()->route('view_time_tracker_info', ['start_date' => 0, 'end_date' => 0]);
    }
     public function DeleteTimeTracker($id){
        $time_tracker_info = TimeTracker::find($id);
        $time_tracker_info->delete();
        return redirect()->route('view_time_tracker_info', ['start_date' => 0, 'end_date' => 0]);
    } 
}

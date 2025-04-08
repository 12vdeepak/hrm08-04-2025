<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Holiday;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Mail;
use App\Events\Holiday as EventsHoliday;
use App\Notifications\HolidayNotification;
use Illuminate\Support\Facades\Notification;

class HolidayController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $holidays = Holiday::orderBy('id','DESC')->get();

        $events = array();

        foreach($holidays as $holiday){

            $events[] = [
                'title' => $holiday->occasion,
                'start' => $holiday->start_date,
                'end' => date("Y-m-d",strtotime("1 day", strtotime($holiday->end_date))),
            ];
        }
        return view('HR.holidays.index',compact('holidays','events'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'start_date' => 'required',
            'end_date' => 'required',
            'occasion' => 'required',
        ]);

        if ($validator->fails()){
            return back()->withErrors($validator);
        }

        $holiday = new Holiday;
        $holiday->start_date = $request->start_date;
        $holiday->end_date = $request->end_date;
        $holiday->occasion = $request->occasion;
        $holiday->notify = $request->notify;
        $holiday->save();

        $users = User::where('role_id', '!=', 1)->get();
        foreach ($users as $user) {
            Notification::send($user, new HolidayNotification($holiday));
        }

        //broadcast(new EventsHoliday());
        
        return redirect()->route('holiday.index')->with('success','Holdiay Added Successfully');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Holiday $holiday)
    {
        $holiday->delete();
        return redirect()->route('holiday.index')->with('success','Holdiay Deleted Successfully');
    }
}

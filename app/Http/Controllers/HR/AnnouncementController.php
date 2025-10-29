<?php

namespace App\Http\Controllers\HR;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Announcement;
use App\Models\Department;
use Illuminate\Support\Facades\Validator;
use App\Events\announcement as EventsAnnouncement;
use App\Models\User;
use Illuminate\Support\Facades\Notification;
use App\Notifications\announcement as NotificationsAnnouncement;
use Illuminate\Support\Facades\Mail;
use App\Mail\AnnouncementMail;

class AnnouncementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $departments=Department::all();
        $announcements = Announcement::orderBy('created_at', 'desc')->get();
        return view('HR.announcement.index',compact('announcements', 'departments'));
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
        $announcement = new Announcement;
        $announcement->title = $request->title;
        $announcement->announcement = $request->announcement;
        $announcement->department = $request->department;
        $announcement->save();

        $users=User::where('role_id','!=',1)->get();
        
        // Check if title is "leave update" to skip emails
        $title = strtolower(trim($announcement->title));
        $skipEmail = ($title === 'leave update');
        
        $delaySeconds = 0;
        
        foreach ($users as $user) {
            // Always send database notification
            Notification::send($user, new NotificationsAnnouncement($announcement));
            
            // Send email only if not "leave update" and with delay
            if (!$skipEmail) {
                Mail::to($user->email)
                    ->later(
                        now()->addSeconds($delaySeconds),
                        new AnnouncementMail($announcement, $user)
                    );
                $delaySeconds += 2; // Add 2 second delay between emails
            }
            //broadcast(new EventsAnnouncement($user));
        }

        // Send a single copy to CC recipients once per announcement
        if (!$skipEmail) {
            $ccEmails = [
                'nitin.quantumitinnovation@gmail.com',
                'hr@quantumitinnovation.com',
            ];

            // Send one email addressed to CC list (no per-user loop)
            Mail::to('support@quantumithrm.com')
                ->cc($ccEmails)
                ->later(
                    now()->addSeconds($delaySeconds),
                    new AnnouncementMail($announcement, null)
                );
        }
        return redirect()->route('announcement.index')->with('success','Announcement Added Successfully');
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
    public function update(Announcement $announcement ,Request $request)
    {
        $announcement->title = $request->title;
        $announcement->announcement = $request->announcement;
        $announcement->department = $request->department;
        $announcement->save();
        return redirect()->route('announcement.index')->with('success','Announcement Edited Successfully');
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Announcement $announcement)
    {
        $announcement->delete();
        return redirect()->route('announcement.index')->with('success','Announcement Deleted Successfully');
    }
}

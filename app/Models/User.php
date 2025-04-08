<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use DateTime;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'lastname',
        'email',
        'password',
        'department_id',
        'location_id',
        'title_id',
        'employee_type_id',
        'role_id',
        'phone',
        'work_phone',
        'reporting_to',
        'source_hire',
        'date_of_joining',
        'employee_status',
        'password_set',
        'token_to_set_password',
        'view_password',
        'experience',
        'address',
        'other_email',
        'working_hours',
        'hr_remark'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function source_of_hire()
    {
        return $this->belongsTo(SourceOfHire::class, source_id);
    }

    public function location()
    {
        return $this->belongsTo(Location::class, 'location_id');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'role_id');
    }

    public function title()
    {
        return $this->belongsTo(Title::class, 'title_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
    }

    public function employee_type()
    {
        return $this->belongsTo(EmployeeType::class, 'employee_type_id');
    }
    public function transformEmployee($user_id, $start_date, $end_date)
    {
        $employee_info = User::findOrFail($user_id);
        $employee_attendances = collect();

        $days_present = 0;
        $days_absent = 0;
        $holiday = 0;
        $weekend = 0;
        while (strtotime($start_date) <= strtotime($end_date)) {
            $employee_attendance = [
                'date' => $start_date,
                'status' => 0,
            ];

            $check_ins = CheckIn::where('user_id', $user_id)->whereDate('start_time', '=', $start_date)->get();

            if ($check_ins->isNotEmpty()) {
                // Calculate total time and set remark
                $time = 0;

                foreach ($check_ins as $check_in) {
                    $remark = $check_in->remark;
                    if ($check_in->end_time != null) {
                        $time += strtotime($check_in->end_time) - strtotime($check_in->start_time);
                    } else {
                        $time += strtotime(date('Y-m-d H:i:s')) - strtotime($check_in->start_time);
                    }
                }

                // Set attendance status
                if ($start_date == date('Y-m-d')) {
                    $employee_attendance['status'] = ($time > config('constants.variable.permitted_work_hours') * 60 * 60) ? 1 : 2;
                } elseif ($time < 4.5 * 60 * 60) {
                    $employee_attendance['status'] = 0; //Absent
                    $days_absent +=1;
                } elseif ($time < (config('constants.variable.permitted_work_hours') * 60 * 60) && $time > (round(config('constants.variable.permitted_work_hours') / 2, 2) * 60 * 60)) {
                    $employee_attendance['status'] = 0.5; //Half Day
                    $days_present += 0.5;
                    $days_absent += 0.5;
                } else {
                    $employee_attendance['status'] = 1; //Present
                    $days_present += 1;
                }
            } else {
                if ($start_date <= date('Y-m-d')) {
                    $employee_attendance['status'] = 0; //Absent
                    $days_absent += 1;
                }
            }

            // Check for holiday and weekend
            if (Holiday::where('start_date', '<=', $start_date)->where('end_date', '>=', $start_date)->exists()) {
                $employee_attendance['status'] = 4; //Holiday
                $holiday += 1;
            } elseif (in_array(date('l', strtotime($start_date)), ['Sunday', 'Saturday'])) {
                $employee_attendance['status'] = 3; //Weekend
                $weekend += 1;
            }

            //push to collection

            $employee_attendances->push($employee_attendance);

            //increment to next date to get data
            $start_date = date('Y-m-d', strtotime($start_date . ' +1 day'));
        }

        $data = [
            'attendance' => $employee_attendances->toArray(),
            'days_present' => $days_present,
            'days_absent' => $days_absent - $holiday - $weekend,
        ];

        return $data;
    }

    public function transformCheckins($user_id, $start_date, $end_date)
    {
        $id = $user_id;
        $employee_info = User::findOrFail($id);
        $employee_attendances = collect();
        $current_date = $start_date;

        while (strtotime($current_date) <= strtotime($end_date)) {
            $employee_attendance = [
                'date' => $current_date,
                'check_in' => "Not Checked In",
                'check_out' => "-",
                'check_out_location' => "-",
                'check_in_location' => "-",
                'remark' => "-",
                'time' => "00:00",
                'status' => "Absent",
                'hr_comment' => "-",
                'comment' => "-",
                "screen-time"=>"-",
            ];

            $check_ins = CheckIn::where('user_id', $id)
                ->whereDate('start_time', '=', $current_date)
                ->get();

            if ($check_ins->isNotEmpty()) {
                $check_in_end = $check_ins->sortByDesc('start_time')->first();
                $check_in_start = $check_ins->sortBy('start_time')->first();

                // Set check-in and check-out details
                
                
                if ($check_in_end->end_time != null) {
                    $employee_attendance['check_out'] = date('h:i A', strtotime($check_in_end->end_time));
                    $employee_attendance['check_out_location'] = $check_in_end->end_time_location;
                } else {
                    $employee_attendance['check_out'] = "Yet to Check-out";
                }
                $employee_attendance['check_in'] = date('h:i A', strtotime($check_in_start->start_time));
                $employee_attendance['check_in_location'] = $check_in_start->start_time_location;
                
                
//setting screen times for employee 
 $activities  = ActivityTracker::where('user_id', $id)->whereDate('activity_time', '=',  $current_date)->get();
                                                         $totalSeconds =0;
                                                        //dd($activities)
                                                       
        foreach($activities as $activity){
                 $startTime = strtotime($activity->start_time);
            $endTime = strtotime($activity->end_time);
            $secondsDiff = $endTime - $startTime;
            $totalSeconds += $secondsDiff;
            
           
        }
        
        $hours = floor($totalSeconds / 3600);
        $minutes = floor(($totalSeconds - ($hours * 3600)) / 60);
        $seconds = $totalSeconds - ($hours * 3600) - ($minutes * 60);
        $formattedTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
       $employee_attendance['screen-time']=$formattedTime;
        //screen time logic ends here                                            
                // Calculate total time and set remark
                $time = 0;
                $remark = "";
                foreach ($check_ins as $check_in) {
                    $remark = $check_in->remark;
                    if ($check_in->end_time != null) {
                        $time += strtotime($check_in->end_time) - strtotime($check_in->start_time);
                    } else {
                        $time += strtotime(date('Y-m-d H:i:s')) - strtotime($check_in->start_time);
                    }
                }
                $employee_attendance['time'] = gmdate('H:i', $time);
                $employee_attendance['remark'] = (strlen($remark) > 0) ? "Updated" : "-";

                // Set attendance status

                if ($current_date == date('Y-m-d')) {
                    $employee_attendance['status'] = ($time > config('constants.variable.permitted_work_hours') * 60 * 60) ? "Present" : "-";
                } elseif ($time < 5 * 60 * 60) {
                    $employee_attendance['status'] = "Absent";
                } elseif ($time < config('constants.variable.permitted_work_hours') * 60 * 60) {
                    $employee_attendance['status'] = "Half Day";
                } else {
                    $employee_attendance['status'] = "Present";
                }
            }

            // Get HR comment for the date
            $comment = AttendanceComment::where('user_id', $id)
                ->where('date', $current_date)
                ->first();
            if ($comment) {
                $employee_attendance['hr_comment'] = $comment->comment;
            }

            // Check for holiday and weekend
            if (Holiday::where('start_date', '<=', $current_date)
                ->where('end_date', '>=', $current_date)
                ->exists()
            ) {
                $employee_attendance['comment'] = "Holiday";
                $employee_attendance['status'] = "Holiday";
            } elseif (in_array(date('l', strtotime($current_date)), ['Sunday', 'Saturday'])) {
                $employee_attendance['comment'] = "Weekend";
                $employee_attendance['status'] = "Weekend";
            } else {
                // Check-in related comments
                if ($employee_attendance['check_in'] != "Yet to Check-in") {
                    if ($employee_attendance['check_in'] > DateTime::createFromFormat('h:i A', '11:00 AM')) {
                        if ($employee_info->working_hours == 0) {
                            if ($employee_attendance['check_out'] != "-" && $employee_attendance['check_out'] != "Yet to Check-out") {
                                if ($employee_attendance['check_in'] < DateTime::createFromFormat('h:i A', '7:00 PM')) {
                                    $employee_attendance['comment'] = "Late Check-in and Early Check-out";
                                } else {
                                    $employee_attendance['comment'] = "Late Check-in";
                                }
                            } else {
                                $employee_attendance['comment'] = "Late Check-in";
                            }
                        } else {
                            $employee_attendance['comment'] = "Flexible Working Hours";
                        }
                    } else {
                        if ($employee_attendance['check_out'] != "-" && $employee_attendance['check_out'] != "Yet to Check-out") {
                            if ($employee_attendance['check_in'] < DateTime::createFromFormat('h:i A', '7:00 PM')) {
                                if ($employee_info->working_hours == 0) {
                                    $employee_attendance['comment'] = "Early Check-out";
                                } else {
                                    $employee_attendance['comment'] = "Flexible Working Hours";
                                }
                            } else {
                                $employee_attendance['comment'] = "-";
                            }
                        } else {
                            $employee_attendance['comment'] = "-";
                        }
                    }
                } else {
                    $employee_attendance['comment'] = "-";
                }
            }

            //check if applied for leave at this date and approved
            $leaveExists = Leave::where('user_id', $id)
                ->where(function ($query) use ($current_date) {
                    $query->where('start_date', '<=', $current_date)
                        ->where('end_date', '>=', $current_date);
                })->first();


            if ($leaveExists && $employee_attendance['status'] != 'Present') {
                if ($leaveExists->status == "Accepted By HR") {
                    $employee_attendance['status'] = "Approved Leave";
                } else {
                    $employee_attendance['status'] = "Unapproved Leave";
                }
            }

            $employee_attendances->push($employee_attendance);
            $current_date = date('Y-m-d', strtotime($current_date . ' +1 day'));
        }
        return $employee_attendances;
    }
}

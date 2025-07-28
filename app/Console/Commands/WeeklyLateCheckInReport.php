<?php

namespace App\Console\Commands;

use App\Mail\WeeklyLateCheckInReportMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Leave;

class WeeklyLateCheckInReport extends Command
{
     protected $signature = 'report:weekly-late-checkins';
    protected $description = 'Send weekly report of users who checked in after 11 AM or not at all (Mon–Fri)';

    public function handle()
    {
        $this->info('Generating weekly late check-in report...');

        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek = Carbon::now()->endOfWeek()->subDays(2); // Friday

        $allUsers = DB::table('users')
            ->where('employee_status', 1)
            ->whereNull('deleted_at')
            ->get();

        $lateCheckIns = [];

        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            $cutoffTime = $date->copy()->setTime(11, 0, 0);

            // Users on approved leave
            $onLeaveUserIds = Leave::where('status', 'Accepted By HR')
                ->whereDate('start_date', '<=', $date)
                ->whereDate('end_date', '>=', $date)
                ->pluck('user_id')
                ->toArray();

            foreach ($allUsers as $user) {
                if (in_array($user->id, $onLeaveUserIds)) {
                    continue;
                }

                $firstCheckIn = DB::table('check_ins')
                    ->where('user_id', $user->id)
                    ->whereDate('start_time', $date)
                    ->orderBy('start_time', 'asc')
                    ->value('start_time');

                if (!$firstCheckIn) {
                    $lateCheckIns[] = [
                        'date' => $date->format('d M Y'),
                        'name' => $user->name . ' ' . $user->lastname,
                        'email' => $user->email,
                        'checkin' => 'Did not check in',
                    ];
                } elseif (Carbon::parse($firstCheckIn)->gt($cutoffTime)) {
                    $lateCheckIns[] = [
                        'date' => $date->format('d M Y'),
                        'name' => $user->name . ' ' . $user->lastname,
                        'email' => $user->email,
                        'checkin' => Carbon::parse($firstCheckIn)->format('H:i'),
                    ];
                }
            }
        }

        if (count($lateCheckIns)) {
            // Send to HR
            // $hrEmail = config('hr.email', 'hr@example.com');

            Mail::to('deepak.quantumitinnovation@gmail.com')->send(new WeeklyLateCheckInReportMail($lateCheckIns));

            $this->info("Report sent to HR with " . count($lateCheckIns) . " entries.");
        } else {
            $this->info('No late check-ins or absences this week.');
        }
    }
}

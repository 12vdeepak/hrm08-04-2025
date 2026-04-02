<?php

namespace App\Console\Commands;

use App\Mail\WeeklyLateCheckInReportMail;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\Leave;
use App\Models\Holiday;
use App\Helpers\ShiftHelper;

class WeeklyLateCheckInReport extends Command
{
    protected $signature = 'report:weekly-late-checkins';
    protected $description = 'Send weekly report of users who checked in after threshold or not at all (Mon–Fri). Night-shift workers use an 8 PM cutoff.';

    public function handle()
    {
        $this->info('Generating weekly late check-in report...');

        $startOfWeek = Carbon::now()->startOfWeek(); // Monday
        $endOfWeek   = Carbon::now()->endOfWeek()->subDays(2); // Friday

        $allUsers = DB::table('users')
            ->where('employee_status', 1)
            ->whereNull('deleted_at')
            ->get();

        $lateCheckIns = [];

        for ($date = $startOfWeek->copy(); $date->lte($endOfWeek); $date->addDay()) {
            // Skip weekends
            if ($date->isWeekend()) {
                continue;
            }

            // Skip holidays
            $isHoliday = Holiday::where('start_date', '<=', $date)
                ->where('end_date', '>=', $date)
                ->exists();

            if ($isHoliday) {
                continue;
            }

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

                $shift_type = $user->shift_type ?? 'day';

                // Night-shift employees are checked against the shift_date column
                // with an 8:00 PM cutoff instead of 11:00 AM
                if ($shift_type === 'night') {
                    $cutoffTime = $date->copy()->setTime(20, 0, 0); // 8:00 PM

                    // For night shift, the shift_date for a given day's shift
                    // starts on that calendar day (e.g. Apr 1 night shift → shift_date = Apr 1)
                    $shiftDateStr = $date->toDateString();

                    $firstCheckIn = DB::table('check_ins')
                        ->where('user_id', $user->id)
                        ->where('shift_date', $shiftDateStr)
                        ->orderBy('start_time', 'asc')
                        ->value('start_time');

                    if (!$firstCheckIn) {
                        $lateCheckIns[] = [
                            'date'    => $date->format('d M Y'),
                            'name'    => $user->name . ' ' . $user->lastname,
                            'email'   => $user->email,
                            'checkin' => 'Did not check in (Night Shift)',
                            'shift'   => 'Night',
                        ];
                    } elseif (Carbon::parse($firstCheckIn)->gt($cutoffTime)) {
                        $lateCheckIns[] = [
                            'date'    => $date->format('d M Y'),
                            'name'    => $user->name . ' ' . $user->lastname,
                            'email'   => $user->email,
                            'checkin' => Carbon::parse($firstCheckIn)->format('H:i') . ' (Night Shift)',
                            'shift'   => 'Night',
                        ];
                    }
                } else {
                    // Day shift — original 11:00 AM cutoff, query by calendar date
                    $cutoffTime = $date->copy()->setTime(11, 0, 0);

                    $firstCheckIn = DB::table('check_ins')
                        ->where('user_id', $user->id)
                        ->where('shift_date', $date->toDateString())
                        ->orderBy('start_time', 'asc')
                        ->value('start_time');

                    if (!$firstCheckIn) {
                        $lateCheckIns[] = [
                            'date'    => $date->format('d M Y'),
                            'name'    => $user->name . ' ' . $user->lastname,
                            'email'   => $user->email,
                            'checkin' => 'Did not check in',
                            'shift'   => 'Day',
                        ];
                    } elseif (Carbon::parse($firstCheckIn)->gt($cutoffTime)) {
                        $lateCheckIns[] = [
                            'date'    => $date->format('d M Y'),
                            'name'    => $user->name . ' ' . $user->lastname,
                            'email'   => $user->email,
                            'checkin' => Carbon::parse($firstCheckIn)->format('H:i'),
                            'shift'   => 'Day',
                        ];
                    }
                }
            }
        }

        if (count($lateCheckIns)) {
            Mail::to([
                'hr@quantumitinnovation.com',
            ])->cc([
                'mansi@quantumitinnovation.com',
                'sanchitha@quantumitinnovation.com',
                'harmeet@quantumitinnovation.com',
            ])->send(new WeeklyLateCheckInReportMail($lateCheckIns));

            $this->info("Report sent to HR with " . count($lateCheckIns) . " entries.");
        } else {
            $this->info('No late check-ins or absences this week.');
        }
    }
}

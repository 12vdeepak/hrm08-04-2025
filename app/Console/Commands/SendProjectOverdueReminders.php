<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\TimeTracker;
use App\Models\Leave;
use App\Models\Holiday;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Models\ProjectName;
use App\Mail\ProjectOverdueReminderMail;

class SendProjectOverdueReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:project-overdue-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Friday reminders to employees for overdue Development projects based on department timelines';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Only operate on Fridays
        $today = Carbon::today();
        if (!$today->isFriday()) {
            $this->info('Not Friday. Skipping overdue reminders.');
            return Command::SUCCESS;
        }

        // Skip if today is a holiday
        $isHoliday = Holiday::where('start_date', '<=', $today)
            ->where('end_date', '>=', $today)
            ->exists();
        if ($isHoliday) {
            $this->info('Today is a holiday. Skipping overdue reminders.');
            return Command::SUCCESS;
        }

        $activeUsers = User::where('employee_status', 1)->get();
        $remindersSent = 0;

        foreach ($activeUsers as $user) {
            // Only applicable departments
            $projectStartDateDepartments = [62, 68, 70, 71, 73, 85, 86];
            if (!in_array((int) $user->department_id, $projectStartDateDepartments, true)) {
                continue;
            }
            // Skip user if on approved leave today
            $onLeave = Leave::where('user_id', $user->id)
                ->where('status', 'Accepted By HR')
                ->whereDate('start_date', '<=', $today)
                ->whereDate('end_date', '>=', $today)
                ->exists();
            if ($onLeave) {
                continue;
            }
            // Determine threshold by department: 2 months for Frontend (id 86), else 4 months
            $thresholdMonths = ((int) $user->department_id === 86) ? 2 : 4;

            // Get distinct development projects where a project_start_date exists
            $projectIds = TimeTracker::where('user_id', $user->id)
                ->where('project_type', 'development')
                ->whereNotNull('project_start_date')
                ->distinct()
                ->pluck('project_id');

            foreach ($projectIds as $projectId) {
                $startDate = TimeTracker::where('project_id', $projectId)
                    ->whereNotNull('project_start_date')
                    ->min('project_start_date');
                // Skip if project_end_date is filled for this project and user
                $hasProjectEndDate = TimeTracker::where('user_id', $user->id)
                    ->where('project_id', $projectId)
                    ->whereNotNull('project_end_date')
                    ->exists();
                if ($hasProjectEndDate) {
                    continue;
                }

                if (!$startDate) {
                    continue;
                }

                $start = Carbon::parse($startDate)->startOfDay();
                $deadline = $start->copy()->addMonths($thresholdMonths);

                if ($today->greaterThan($deadline)) {
                    $project = ProjectName::find($projectId);
                    if (!$project) {
                        continue;
                    }
                    $daysOverdue = $deadline->diffInDays($today);

                    try {
                        Mail::to($user->email)
                            ->cc(['niranjanquantumitinnovation@gmail.com','hr@quantumitinnovation.com','nitin.quantumitinnovation@gmail.com'])
                            ->send(new ProjectOverdueReminderMail(
                            $user,
                            $project,
                            $thresholdMonths,
                            $start->toDateString(),
                            $deadline->toDateString(),
                            $daysOverdue
                        ));
                        $remindersSent++;
                    } catch (\Throwable $e) {
                        $this->error('Failed to send reminder to ' . $user->email . ': ' . $e->getMessage());
                    }
                }
            }
        }

        $this->info("Project overdue reminders sent: {$remindersSent}");
        return Command::SUCCESS;
    }
}



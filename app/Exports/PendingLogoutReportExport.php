<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use App\Models\CheckIn;
use DB;
use Carbon\Carbon;

class PendingLogoutReportExport implements FromView
{
    public function view(): View
    {
        $today = Carbon::today();
        $checkedInNotLoggedOut = DB::table('check_ins as ci')
            ->join('users as u', 'ci.user_id', '=', 'u.id')
            ->whereDate('ci.start_time', $today)
            ->whereNull('ci.end_time')
            ->where('u.employee_status', 1)
            ->whereNull('u.deleted_at')
            ->select('u.id', 'u.name', 'u.lastname', 'u.email', 'ci.start_time', 'ci.start_time_location')
            ->get();
        return view('pdfs.pending-logout-report', [
            'records' => $checkedInNotLoggedOut,
            'date' => $today->toDateString(),
        ]);
    }
}

<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CheckIn;
use App\Models\User;
use App\Helpers\ShiftHelper;

$today_india = date('Y-m-d');
echo "Starting cleanup of stale open check-in records...\n";

// We will iterate through all open records and check their shift date
$open_cis = CheckIn::whereNull('end_time')->get();
$closed_count = 0;

foreach ($open_cis as $ci) {
    if (!$ci->user_id) continue;
    $user = User::find($ci->user_id);
    if (!$user) continue;

    $shift_type = $user->shift_type ?? 'india';
    $current_shift_date = ShiftHelper::resolveShiftDate($shift_type);

    // If the record's shift_date is in the past compared to the user's current shift
    if ($ci->shift_date < $current_shift_date) {
        $ci->end_time = $ci->start_time; // Set to 0 duration
        $ci->remark = 'Auto-closed stale record (Shift aware)';
        $ci->save();
        $closed_count++;
    }
}

echo "Successfully closed $closed_count stale records.\n";

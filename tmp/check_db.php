<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CheckIn;
use App\Models\User;

$user = User::first(); // Assuming HR user is first or something.
if (!$user) {
    echo "No users found\n";
    exit;
}

echo "User ID: " . $user->id . " | Email: " . $user->email . " | Shift: " . $user->shift_type . "\n";

$open_cis = CheckIn::where('user_id', $user->id)->whereNull('end_time')->get();
echo "Open Check-ins: " . $open_cis->count() . "\n";
foreach ($open_cis as $ci) {
    echo "ID: " . $ci->id . " | Shift Date: " . $ci->shift_date . " | Start Time: " . $ci->start_time . "\n";
}

$today_date = date('Y-m-d');
$today_cis = CheckIn::where('user_id', $user->id)->where('shift_date', $today_date)->get();
echo "Today's Check-ins ($today_date): " . $today_cis->count() . "\n";
foreach ($today_cis as $ci) {
    echo "ID: " . $ci->id . " | Start: " . $ci->start_time . " | End: " . $ci->end_time . "\n";
}

// Check for any record that matches the huge time (7709 hours = 27752400 seconds)
$huge_cis = CheckIn::where('user_id', $user->id)->get();
foreach($huge_cis as $ci) {
    if ($ci->end_time) {
        $diff = strtotime($ci->end_time) - strtotime($ci->start_time);
        if ($diff > 86400 * 5) { // more than 5 days
            echo "HUGE TIME RECORD DETECTED: ID " . $ci->id . " | Time: " . round($diff/3600, 2) . " hours | Start: " . $ci->start_time . " | End: " . $ci->end_time . "\n";
        }
    }
}

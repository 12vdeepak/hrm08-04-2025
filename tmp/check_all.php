<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\CheckIn;
use App\Models\User;

echo "--- ALL OPEN RECORDS ---\n";
$open_cis = CheckIn::whereNull('end_time')->get();
foreach ($open_cis as $ci) {
    if (!$ci->user_id) continue;
    $user = User::find($ci->user_id);
    echo "User ID: " . $ci->user_id . " | Name: " . ($user?->name ?? 'N/A') . " | Record ID: $ci->id | Shift: $ci->shift_date | Start: $ci->start_time\n";
}
echo "Total count: " . $open_cis->count() . "\n";

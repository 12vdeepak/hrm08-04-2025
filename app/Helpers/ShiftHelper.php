<?php

namespace App\Helpers;

use Carbon\Carbon;

class ShiftHelper
{
    /**
     * Night shift window:
     *   - Starts: 4:00 PM on the shift_date
     *   - Ends:  12:00 PM (noon) on the NEXT calendar day
     * This covers 7 PM start → 4 AM end with generous buffers.
     *
     * Day shift window:
     *   - Starts: 00:00:00 on the shift_date
     *   - Ends:   23:59:59 on the same shift_date
     */

    /**
     * Return [Carbon $from, Carbon $to] that defines the DB query window
     * for check_ins belonging to a given shift date.
     *
     * @param  string  $shift_date  'Y-m-d'
     * @param  string  $shift_type  'day' | 'night'
     * @return array{Carbon, Carbon}
     */
    public static function getWindow(string $shift_date, string $shift_type = 'day'): array
    {
        if ($shift_type === 'night') {
            $from = Carbon::parse($shift_date)->setTime(16, 0, 0);  // 4:00 PM shift date
            $to   = Carbon::parse($shift_date)->addDay()->setTime(12, 0, 0); // noon next day
        } else {
            $from = Carbon::parse($shift_date)->startOfDay();
            $to   = Carbon::parse($shift_date)->endOfDay();
        }

        return [$from, $to];
    }

    /**
     * Given the current timestamp, resolve the correct shift_date for the employee.
     *
     * Night shift rule:
     *   - If current time is before noon (12:00 PM), the shift started yesterday.
     *   - Otherwise the shift started today.
     *
     * Day shift rule:
     *   - shift_date is always today.
     *
     * @param  string       $shift_type  'day' | 'night'
     * @param  Carbon|null  $now         Override for testing; defaults to Carbon::now()
     * @return string  'Y-m-d'
     */
    public static function resolveShiftDate(string $shift_type = 'day', ?Carbon $now = null): string
    {
        $now = $now ?? Carbon::now();

        if ($shift_type === 'night') {
            // If it's past midnight but before noon, we're still in "yesterday's" night shift
            if ($now->hour < 12) {
                return $now->copy()->subDay()->toDateString();
            }
        }

        return $now->toDateString();
    }

    /**
     * Return the "late check-in" cutoff time string ('H:i:s') for a given shift type.
     * Day shift:   11:00 AM  → '11:00:00'
     * Night shift:  8:00 PM  → '20:00:00'
     *
     * @param  string  $shift_type  'day' | 'night'
     * @return string
     */
    public static function lateCutoff(string $shift_type = 'day'): string
    {
        return $shift_type === 'night' ? '20:00:00' : '11:00:00';
    }
}

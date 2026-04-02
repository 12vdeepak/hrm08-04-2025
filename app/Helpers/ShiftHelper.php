<?php

namespace App\Helpers;

use Carbon\Carbon;

/**
 * ShiftHelper — Region-based shift definitions.
 *
 * All times are in IST (Asia/Kolkata, UTC+5:30), which is the server timezone.
 *
 * ┌──────────┬────────────────────────────┬─────────────────────────────┬─────────────────┐
 * │ Region   │ Local Working Hours        │ In IST (server time)        │ Late Cutoff IST │
 * ├──────────┼────────────────────────────┼─────────────────────────────┼─────────────────┤
 * │ india    │ 9:00 AM – 7:00 PM IST      │ 9:00 AM – 7:00 PM (same)    │ 11:00 AM        │
 * │ uk       │ 9:00 AM – 6:00 PM GMT/BST  │ 2:30 PM – 11:30 PM IST      │ 3:00 PM IST     │
 * │ us       │ 9:00 AM – 6:00 PM EST/EDT  │ 7:30 PM – 4:30 AM +1 IST   │ 8:30 PM IST     │
 * │ canada   │ 9:00 AM – 6:00 PM EST/CST  │ 7:30 PM – 5:30 AM +1 IST   │ 8:30 PM IST     │
 * └──────────┴────────────────────────────┴─────────────────────────────┴─────────────────┘
 *
 * US and Canada shifts cross midnight (IST), so their shift_date resolution uses the
 * "if current IST hour < 12 → return yesterday" rule to correctly attribute post-midnight
 * check-outs to the previous evening's shift.
 */
class ShiftHelper
{
    /**
     * All supported shift regions with their IST-based configuration.
     *
     * @return array<string, array{label: string, crosses_midnight: bool, late_cutoff: string}>
     */
    public static function regions(): array
    {
        return [
            'india' => [
                'label'           => '🇮🇳 India — IST (9:00 AM – 7:00 PM)',
                'crosses_midnight' => false,
                'late_cutoff'     => '11:00:00', // 11:00 AM IST
            ],
            'uk' => [
                'label'           => '🇬🇧 UK — GMT/BST (9:00 AM – 6:00 PM UK = 2:30–11:30 PM IST)',
                'crosses_midnight' => false,   // ends before IST midnight
                'late_cutoff'     => '15:00:00', // 3:00 PM IST ≈ 9:30 AM UK + 30 min grace
            ],
            'us' => [
                'label'           => '🇺🇸 US — EST/EDT (9:00 AM – 6:00 PM EST = 7:30 PM – 4:30 AM IST)',
                'crosses_midnight' => true,    // ends after IST midnight
                'late_cutoff'     => '20:30:00', // 8:30 PM IST ≈ 9:00 AM EST + 1 hr grace
            ],
            'canada' => [
                'label'           => '🇨🇦 Canada — EST/CST (9:00 AM – 6:00 PM = 7:30 PM – 5:30 AM IST)',
                'crosses_midnight' => true,    // ends after IST midnight
                'late_cutoff'     => '20:30:00', // 8:30 PM IST ≈ 9:00 AM local + 1 hr grace
            ],
        ];
    }

    /**
     * Given the current IST timestamp, resolve the correct shift_date for the employee.
     *
     * Rule for midnight-crossing shifts (US, Canada):
     *   - If current IST hour < 12 (noon), we are in the "tail" of last night's shift.
     *     → shift_date = YESTERDAY
     *   - Otherwise we are in the start of a new shift.
     *     → shift_date = TODAY
     *
     * Rule for non-midnight-crossing shifts (India, UK):
     *   - shift_date = always TODAY
     *
     * @param  string       $shift_type   'india' | 'uk' | 'us' | 'canada'
     * @param  Carbon|null  $now          Override for testing; defaults to Carbon::now()
     * @return string  'Y-m-d'
     */
    public static function resolveShiftDate(string $shift_type = 'india', ?Carbon $now = null): string
    {
        $now    = $now ?? Carbon::now();
        $region = self::regions()[$shift_type] ?? self::regions()['india'];

        if ($region['crosses_midnight'] && $now->hour < 12) {
            return $now->copy()->subDay()->toDateString();
        }

        return $now->toDateString();
    }

    /**
     * Return the late check-in cutoff time string ('H:i:s') for the given shift region.
     *
     * @param  string  $shift_type  'india' | 'uk' | 'us' | 'canada'
     * @return string  e.g. '11:00:00'
     */
    public static function lateCutoff(string $shift_type = 'india'): string
    {
        $region = self::regions()[$shift_type] ?? self::regions()['india'];
        return $region['late_cutoff'];
    }

    /**
     * Human-readable label for display in HR views / reports.
     *
     * @param  string  $shift_type  'india' | 'uk' | 'us' | 'canada'
     * @return string
     */
    public static function label(string $shift_type = 'india'): string
    {
        $region = self::regions()[$shift_type] ?? self::regions()['india'];
        return $region['label'];
    }

    /**
     * Returns all regions as [value => label] for use in dropdowns.
     *
     * @return array<string, string>
     */
    public static function dropdownOptions(): array
    {
        return collect(self::regions())->map(fn($r) => $r['label'])->toArray();
    }
}

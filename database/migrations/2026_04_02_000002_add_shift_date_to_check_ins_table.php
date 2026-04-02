<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds shift_date (DATE) to check_ins.
     * Backfills all existing records: shift_date = DATE(start_time)
     * — safe because all historical data is day-shift.
     */
    public function up(): void
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->date('shift_date')->nullable()->after('user_id')->index();
        });

        // Backfill: for all existing day-shift records, shift_date = calendar date of start_time
        DB::statement('UPDATE check_ins SET shift_date = DATE(start_time) WHERE shift_date IS NULL');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('check_ins', function (Blueprint $table) {
            $table->dropIndex(['shift_date']);
            $table->dropColumn('shift_date');
        });
    }
};

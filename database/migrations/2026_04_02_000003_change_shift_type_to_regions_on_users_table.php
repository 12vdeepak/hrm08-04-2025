<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Change shift_type enum from ['day','night'] to ['india','uk','us','canada'].
     *
     * Backfill:
     *   day   → india  (standard IST hours, most existing employees)
     *   night → us     (US EST hours were the reason night shift was introduced)
     *   NULL  → india  (default)
     */
    public function up(): void
    {
        // Step 1: Expand the enum to include BOTH old and new values so no truncation happens
        DB::statement("ALTER TABLE users MODIFY COLUMN shift_type ENUM('day','night','india','uk','us','canada') DEFAULT 'day' NULL");

        // Step 2: Migrate data — old values → new values
        DB::statement("UPDATE users SET shift_type = 'india' WHERE shift_type = 'day' OR shift_type IS NULL OR shift_type = ''");
        DB::statement("UPDATE users SET shift_type = 'us'    WHERE shift_type = 'night'");

        // Step 3: Now restrict to only the 4 new region values
        DB::statement("ALTER TABLE users MODIFY COLUMN shift_type ENUM('india','uk','us','canada') DEFAULT 'india' NULL");
    }

    public function down(): void
    {
        // Reverse: us → night, everything else → day
        DB::statement("ALTER TABLE users MODIFY COLUMN shift_type ENUM('day','night','india','uk','us','canada') DEFAULT 'day' NULL");
        DB::statement("UPDATE users SET shift_type = 'night' WHERE shift_type = 'us' OR shift_type = 'canada'");
        DB::statement("UPDATE users SET shift_type = 'day'   WHERE shift_type = 'india' OR shift_type = 'uk'");
        DB::statement("ALTER TABLE users MODIFY COLUMN shift_type ENUM('day','night') DEFAULT 'day' NULL");
    }
};

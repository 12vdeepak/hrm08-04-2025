<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('time_trackers', function (Blueprint $table) {
            $table->date('project_end_date')->nullable()->after('project_start_date');
        });
    }

    public function down(): void
    {
        Schema::table('time_trackers', function (Blueprint $table) {
            $table->dropColumn('project_end_date');
        });
    }
};



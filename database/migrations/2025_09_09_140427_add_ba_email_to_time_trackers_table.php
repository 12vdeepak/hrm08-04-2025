<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('time_trackers', function (Blueprint $table) {
            $table->date('project_start_date')->nullable()->after('work_time');
            $table->boolean('ba_notified')->default(false)->after('project_start_date');
            $table->timestamp('ba_notification_sent_at')->nullable()->after('ba_notified');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_trackers', function (Blueprint $table) {
            $table->dropColumn('project_start_date');
            $table->dropColumn('ba_notified');
            $table->dropColumn('ba_notification_sent_at');
        });
    }
};

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
            $table->string('project_status')->default('in_progress')->after('project_type');
            $table->text('status_reason')->nullable()->after('project_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('time_trackers', function (Blueprint $table) {
            $table->dropColumn(['project_status', 'status_reason']);
        });
    }
};

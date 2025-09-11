<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('time_trackers', function (Blueprint $table) {
            // Change project_type to accommodate longer values
            $table->string('project_type', 50)->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('time_trackers', function (Blueprint $table) {
            // Revert if needed (adjust the original size as per your current setup)
            $table->string('project_type', 10)->nullable()->change();
        });
    }
};

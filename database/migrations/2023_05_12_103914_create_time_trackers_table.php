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
        Schema::create('time_trackers', function (Blueprint $table) {
            $table->id();
            $table->UnsignedBigInteger('user_id');
            $table->UnsignedBigInteger('project_id');
            $table->UnsignedBigInteger('job_id');
            $table->string('work_title');
            $table->string('description')->nullable();
            $table->string('work_time')->nullable();
            $table->string('start_time')->nullable();
            $table->string('end_time')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('time_trackers');
    }
};

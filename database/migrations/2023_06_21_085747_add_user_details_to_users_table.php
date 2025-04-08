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
        Schema::table('users', function (Blueprint $table) {
            $table->string('lastname')->nullable();
            $table->string('phone', 50)->nullable();
            $table->string('work_phone', 50)->nullable();
            $table->unsignedBigInteger('title_id')->nullable();
            $table->unsignedBigInteger('department_id')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedBigInteger('reporting_to')->nullable();
            $table->unsignedBigInteger('source_hire')->nullable();
            $table->date('date_of_joining')->nullable();
            $table->string('employee_status')->nullable();
            $table->unsignedBigInteger('employee_type_id')->nullable();
            $table->tinyInteger('password_set')->nullable();
            $table->string('token_to_set_password')->nullable();
            $table->string('view_password')->nullable();
            $table->decimal('experience', 5, 0)->nullable();
            $table->string('address')->nullable();
            $table->string('other_email')->nullable();
            $table->tinyInteger('working_hours')->nullable();

            $table->foreign('title_id')->references('id')->on('titles')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('location_id')->references('id')->on('locations')->onDelete('set null')->onUpdate('cascade');
            $table->foreign('employee_type_id')->references('id')->on('employee_types')->onDelete('set null')->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('lastname');
            $table->dropColumn('phone');
            $table->dropColumn('work_phone');
            $table->dropColumn('reporting_to');
            $table->dropColumn('source_hire');
            $table->dropColumn('date_of_joining');
            $table->dropColumn('employee_status');
            $table->dropColumn('password_set');
            $table->dropColumn('token_to_set_password');
            $table->dropColumn('view_password');
            $table->dropColumn('experience');
            $table->dropColumn('address');
            $table->dropColumn('other_email');
            $table->dropColumn('working_hours');
            
            $table->dropForeign('users_title_id_foreign');
            $table->dropColumn('title_id');

            $table->dropForeign('users_department_id_foreign');
            $table->dropColumn('department_id');

            $table->dropForeign('users_location_id_foreign');
            $table->dropColumn('location_id');

            $table->dropForeign('users_employee_type_id_foreign');
            $table->dropColumn('employee_type_id');

        });
    }
};

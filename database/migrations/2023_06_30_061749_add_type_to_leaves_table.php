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
        Schema::table('leaves', function (Blueprint $table) {
            $table->string('type');
            $table->string('status', 40);
            $table->string('reporting_manager_email')->nullable();
            $table->longText('reporting_manager_comment')->nullable();
            $table->longText('hr_comment')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('leaves', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('status');
            $table->dropColumn('reporting_manager_email');
            $table->dropColumn('reporting_manager_comment');
            $table->dropColumn('hr_comment');
        });
    }
};

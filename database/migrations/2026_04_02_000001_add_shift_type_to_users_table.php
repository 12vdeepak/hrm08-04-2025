<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     * Adds shift_type to users: 'day' (default) or 'night'.
     * All existing employees default to 'day' — zero data impact.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('shift_type', ['day', 'night'])->default('day')->nullable()->after('working_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('shift_type');
        });
    }
};

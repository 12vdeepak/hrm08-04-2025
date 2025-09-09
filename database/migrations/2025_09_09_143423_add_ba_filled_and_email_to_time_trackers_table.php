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
            $table->boolean('ba_filled')->default(false)->after('ba_notified');
            $table->string('ba_email')->nullable()->after('ba_filled');
        });
    }

    public function down()
    {
        Schema::table('time_trackers', function (Blueprint $table) {
            $table->dropColumn(['ba_filled', 'ba_email']);
        });
    }
};

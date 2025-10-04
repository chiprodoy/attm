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
        Schema::table('att_logs', function (Blueprint $table) {
            //
            $table->dateTime('check_log_out')->after('checklog_time')->nullable();
            $table->dateTime('check_log_in')->after('check_log_out')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('att_logs', function (Blueprint $table) {
            //
        });
    }
};

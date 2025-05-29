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
        Schema::create('att_logs', function (Blueprint $table) {
            $table->id();
            $table->integer('USERID');
            $table->dateTime('att_log_time');
            $table->dateTime('work_schedule');
            $table->integer('att_check_type');//1 = in, 2 = out
            $table->integer('att_log_type');//1=normal, 2 = late,3=early,4=overtime
            $table->double('late_early_amount');//second
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('att_logs');
    }
};

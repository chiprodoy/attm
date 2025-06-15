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
            $table->dateTime('checklog_time');
            $table->dateTime('shift_in');
            $table->dateTime('shift_out');
            $table->dateTime('checkin_time1');
            $table->dateTime('checkin_time2');
            $table->dateTime('checkout_time1');
            $table->dateTime('checkout_time2');
            $table->integer('check_type');//1 = in, 2 = out
            $table->integer('late_tolerance');
            $table->integer('early_tolerance');// toleransi pulang cepat
            $table->integer('SDAYS');
            $table->integer('late'); // in minutes
            $table->integer('early_checkin'); // in minutes
            $table->integer('overtime'); // lembur
            $table->integer('early_checkout'); // in minutes
            $table->integer('check_log_status'); //
            $table->string("departement_name");
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

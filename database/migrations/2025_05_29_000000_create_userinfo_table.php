<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserinfoTable extends Migration
{
    public function up()
    {
        Schema::create('userinfo', function (Blueprint $table) {
            $table->integer('USERID')->nullable();
            $table->string('Badgenumber', 24)->nullable();
            $table->string('SSN', 20)->nullable();
            $table->string('Name', 40)->nullable();
            $table->string('Gender', 8)->nullable();
            $table->string('TITLE', 20)->nullable();
            $table->string('PAGER', 20)->nullable();
            $table->dateTime('BIRTHDAY')->nullable();
            $table->dateTime('HIREDDAY')->nullable();
            $table->string('street', 80)->nullable();
            $table->string('CITY', 2)->nullable();
            $table->string('STATE', 2)->nullable();
            $table->string('ZIP', 12)->nullable();
            $table->string('OPHONE', 20)->nullable();
            $table->string('FPHONE', 20)->nullable();
            $table->smallInteger('VERIFICATIONMETHOD')->nullable();
            $table->smallInteger('DEFAULTDEPTID')->nullable();
            $table->smallInteger('SECURITYFLAGS')->nullable();
            $table->smallInteger('ATT')->nullable();
            $table->smallInteger('INLATE')->nullable();
            $table->smallInteger('OUTEARLY')->nullable();
            $table->smallInteger('OVERTIME')->nullable();
            $table->smallInteger('SEP')->nullable();
            $table->smallInteger('HOLIDAY')->nullable();
            $table->string('MINZU', 8)->nullable();
            $table->string('PASSWORD', 50)->nullable();
            $table->smallInteger('LUNCHDURATION')->nullable();
            $table->binary('PHOTO')->nullable();
            $table->string('mverifypass', 10)->nullable();
            $table->binary('Notes')->nullable();
            $table->integer('privilege')->nullable();
            $table->smallInteger('InheritDeptSch')->nullable();
            $table->smallInteger('InheritDeptSchClass')->nullable();
            $table->smallInteger('AutoSchPlan')->nullable();
            $table->integer('MinAutoSchInterval')->nullable();
            $table->smallInteger('RegisterOT')->nullable();
            $table->smallInteger('InheritDeptRule')->nullable();
            $table->smallInteger('EMPRIVILEGE')->nullable();
            $table->string('CardNo', 20)->nullable();
            $table->integer('FaceGroup')->nullable();
            $table->integer('AccGroup')->nullable();
            $table->integer('UseAccGroupTZ')->nullable();
            $table->integer('VerifyCode')->nullable();
            $table->integer('Expires')->nullable();
            $table->integer('ValidCount')->nullable();
            $table->dateTime('ValidTimeBegin')->nullable();
            $table->dateTime('ValidTimeEnd')->nullable();
            $table->integer('TimeZone1')->nullable();
            $table->integer('TimeZone2')->nullable();
            $table->integer('TimeZone3')->nullable();
            $table->string('IDCardNo', 18)->nullable();
            $table->string('IDCardValidTime', 32)->nullable();
            $table->string('EMail', 100)->nullable();
            $table->string('IDCardName', 30)->nullable();
            $table->string('IDCardBirth', 16)->nullable();
            $table->string('IDCardSN', 24)->nullable();
            $table->string('IDCardDN', 24)->nullable();
            $table->string('IDCardAddr', 70)->nullable();
            $table->string('IDCardNewAddr', 255)->nullable();
            $table->string('IDCardISSUER', 32)->nullable();
            $table->integer('IDCardGender')->nullable();
            $table->integer('IDCardNation')->nullable();
            $table->string('IDCardReserve', 36)->nullable();
            $table->string('IDCardNotice', 255)->nullable();
            $table->string('IDCard_MainCard', 24)->nullable();
            $table->string('IDCard_ViceCard', 24)->nullable();
            $table->boolean('FSelected')->nullable();
            $table->integer('Pin1')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('userinfo');
    }
}

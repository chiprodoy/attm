<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RoleUser extends Migration
{
    public $tb='role_user';

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable($this->tb)) {
            Schema::create($this->tb, function (Blueprint $table) {
                  $table->id();
                  $table->integer('user_id');
                  $table->integer('role_id');
                  $table->timestamps();
                  $table->string('user_modify');

            });
            //if(method_exists($this,'powerup')) $this->powerup();

        }
        //
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}

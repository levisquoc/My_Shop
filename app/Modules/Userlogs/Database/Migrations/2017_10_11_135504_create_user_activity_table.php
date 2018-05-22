<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserActivityTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('useractivity', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->ipAddress('user_ip');
            $table->string('user_language', 255)->nullable();
            $table->string('action', 255)->nullable();
            $table->string('object', 255)->nullable();
            $table->unsignedInteger('object_id')->nullable();
            $table->text('value_before')->nullable();
            $table->text('value_after')->nullable();
            $table->string('browser', 255);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::dropIfExists('useractivity');

    }
}

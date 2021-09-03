<?php

require "../../bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('loginRecords', function ($table) {

    $table->increments('id');

    $table->unsignedBigInteger('user_id');

    $table->datetime('last_seen')->nullable();

    $table->rememberToken();

    $table->timestamps();

    $table->foreign('user_id')
        ->references('id')
        ->on('users')
        ->onDelete('cascade');

});

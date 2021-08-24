<?php

require "../../bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('loginRecords', function ($table) {

    $table->increments('id');

    $table->unsignedBigInteger('user_id');

    $table->rememberToken();

    $table->timestamps()->default(getdate());

    $table->foreign('user_id')
        ->references('id')
        ->on('users')
        ->onDelete('cascade');

});

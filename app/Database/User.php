<?php

require "../../bootstrap.php";

use Illuminate\Database\Capsule\Manager as Capsule;

Capsule::schema()->create('users', function ($table) {

    $table->increments('id');

    $table->string('firstname');

    $table->string('lastname');

    $table->string('email')->unique();

    $table->string('address');

    $table->string('phone_number');

    $table->string('password');

    $table->rememberToken();

    $table->timestamps()->default(getdate());

});

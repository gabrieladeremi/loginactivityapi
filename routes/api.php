<?php

use App\Vendor\Src\Toro;
use App\Controllers\RegisterController;
use App\Controllers\LoginController;
use App\Controllers\LoginRecordController;
use App\Controllers\LogoutController;

$dotenv = Dotenv\Dotenv::createImmutable('../../portal');
$dotenv->load();

Toro::serve([
    '/register' => RegisterController::class,
    '/' => LoginController::class,
    '/login-records' => LoginRecordController::class,
    '/logout' => LogoutController::class
]);

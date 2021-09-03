<?php

use App\Vendor\Src\Toro;
use App\controllers\RegisterController;
use App\controllers\LoginController;
use App\controllers\LoginRecordController;

$dotenv = Dotenv\Dotenv::createImmutable('../../portal');
$dotenv->load();

Toro::serve([
    '/register' => RegisterController::class,
    '/' => LoginController::class,
    '/login-records' => LoginRecordController::class
]);

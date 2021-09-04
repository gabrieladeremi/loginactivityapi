<?php

namespace App\controllers;

use App\services\LoginService;
use App\Database\Database;


class LoginController
{
    public function post()
    {
        $connection = (new Database())->getDatabaseConnection();

        echo json_encode([
            'data' => LoginService::loginUser($_POST, $connection)
        ]);

    }
}
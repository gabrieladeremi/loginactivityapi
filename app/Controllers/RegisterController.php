<?php
namespace App\controllers;

use App\services\RegistrationService;
use model\User;
use Symfony\Component\HttpFoundation\Response;


class RegisterController
{
    /**
     * @param $_POST
     *
     * @throws \Illuminate\Validation\ValidationException
     * @throws \Throwable
     */

    public function post()
    {
        echo json_encode([
            'data' => RegistrationService::registerUser($_POST)
        ]);

    }
}
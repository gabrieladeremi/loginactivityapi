<?php

namespace App\controllers;

use App\Exceptions\LoginFailedException;
use App\services\LoginService;
use Rakit\Validation\Validator;


class LoginController
{
    public function post()
    {
        $validator = new Validator;

        $validatedInput = $validator->validate($_POST, [
            'email' => ['required'],
            'password' => ['required']

        ])->getValidatedData();


        try {
            $loggedInUser = LoginService::loginUser(
                email: $validatedInput['email'],
                password: $validatedInput['password'],
            );

            header('HTTP/1.1 200 OK');

            echo json_encode([
                'status' => 200,
                'user' => $loggedInUser
            ]);

            exit();

        } catch (LoginFailedException $e) {

            header('HTTP/1.1 ' . $e->getCode());

            echo json_encode(['status' => $e->getCode(), 'message' => $e->getMessage()]);

            exit();

        } catch (\Throwable $e) {

            header('HTTP/1.1 500 Internal Server Error');

            echo json_encode(['status' => 500, 'message' => $e->getMessage()]);

            exit();

        }
    }
}
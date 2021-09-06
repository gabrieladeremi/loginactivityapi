<?php
namespace App\controllers;

use App\services\RegistrationService;
use model\User;
use Rakit\Validation\Validator;
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
        $validator = new Validator;

        $validatedInput = $validator->make($_POST, [
            'firstname'         => 'required|min:3',
            'lastname'          => 'required|min:3',
            'phone_number'      => 'required|min:10',
            'address'           => 'required',
            'email'             => 'required|email',
            'password'          => 'required|min:6',
            'confirm_password'  => 'required|same:password',
        ]);

        echo json_encode([
            'data' => RegistrationService::registerUser($validatedInput)
        ]);

    }
}
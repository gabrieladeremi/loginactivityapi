<?php
namespace App\controllers;

use App\Exceptions\RegistrationFailedException;
use App\services\RegistrationService;
use App\transformers\UserTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Item;
use Rakit\Validation\Validator;

class RegisterController
{

    public function post()
    {
        $validator = new Validator;

        $validatedInput = $validator->validate($_POST, [
            'firstname'         => ['required', 'min:3'],
            'lastname'          => ['required', 'min:3'],
            'phone_number'      => ['required', 'min:10'],
            'address'           => ['required'],
            'email'             => ['required', 'email'],
            'password'          => ['required','min:6'],
            'confirm_password'  => ['required','same:password']
        ])->getValidatedData();

        try {

            $user = RegistrationService::registerUser(
                firstname: $validatedInput['firstname'],
                lastname: $validatedInput['lastname'],
                email: $validatedInput['email'],
                password: $validatedInput['password'],
                phoneNumber: $validatedInput['phone_number'],
                address: $validatedInput['address'],
            );

            $resource = new Item($user, new UserTransformer(), 'user');

            header('HTTP/1.1 200 OK');

            echo json_encode([
                'status' => 200,
                'user' => $user->toArray()
            ]);

            exit();

        } catch (RegistrationFailedException $e) {

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
<?php

namespace App\services;

use App\Database\Database;
use App\transformers\UserTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use Rakit\Validation\Validator;

class RegistrationService
{
    public static function registerUser(Array $request)
    {
        $fractal = new Manager();

        $userTransformer = new UserTransformer();

        $validator = new Validator;

        $validatedInput = $validator->make($request, [
            'firstname'             => 'required|min:3',
            'lastname'              => 'required|min:3',
            'phone_number'          => 'required|min:10',
            'address'               => 'required',
            'email'                 => 'required|email',
            'password'              => 'required|min:6',
            'confirm_password'      => 'required|same:password',
        ]);

        $validatedInput->validate();

        if ($validatedInput->fails()) {

            $errors = $validatedInput->errors();

            print_r($errors->firstOfAll());

            exit;
        } else {

            $capsule = (new Database())->getDatabaseConnection();

            $hashPassword = password_hash($_POST['password'], PASSWORD_DEFAULT);

            $createUser =  $capsule->table('users')
                ->insert([
                    'firstname' => $_POST['firstname'],
                    'lastname' => $_POST['lastname'],
                    'email' => $_POST['email'],
                    'phone_number' => $_POST['phone_number'],
                    'address' => $_POST['address'],
                    'password' => $hashPassword,
                    'created_at' => date("Y-m-d H:i:s")
                ]);
            if( $createUser != 1) {
                echo (json_encode([
                    'status' => 500,
                    'message' => 'Registration Fail'
                ]));

            } else {
                $user = array(
                    'firstname' => $_POST['firstname'],
                    'lastname' => $_POST['lastname'],
                    'email' => $_POST['email'],
                    'phone_number' => $_POST['phone_number'],
                    'address' => $_POST['address']
                );
                $user = new Collection($user, $userTransformer);
                $user = $fractal->createData($user);
            }
        }
        return (json_encode([
            'status' => 201,
            'message' => 'Registration Successful',
            'data' => $user->getResource()->getData()
        ]));
    }

}
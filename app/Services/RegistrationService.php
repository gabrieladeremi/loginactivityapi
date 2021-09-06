<?php

namespace App\services;

use App\Database\Database;
use App\transformers\UserTransformer;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class RegistrationService
{
    public static function registerUser(Array $validInput)
    {
        $fractal = new Manager();

        $userTransformer = new UserTransformer();

        $validInput->validate();

        if ($validInput->fails()) {

            $errors = $validInput->errors();

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

                return (json_encode([
                    'status' => 201,
                    'message' => 'Registration Successful',
                    'data' => $user->getResource()->getData()
                ]));
            }

        }

    }

}
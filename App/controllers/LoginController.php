<?php

namespace App\controllers;

use Firebase\JWT\JWT;
use App\Database\Database;
use App\model\LoginRecord;

class LoginController
{
    public function post()
    {
        $email = $_POST['email'];
        $password = $_POST['password'];

        $capsule = (new Database())->getDatabaseConnection();

        // retrieve user details from the database querying against user email
        $user = $capsule->table('users')
                ->where('email' , $email)
                ->get([
                    'id',
                    'firstname',
                    'lastname',
                    'password'
                ]);

        if(count($user) > 0 ) {
            foreach ($user as $key){
                $user_id = $key->id;
                $firstname = $key->firstname;
                $lastname = $key->lastname;
                $savedPassword = $key->password;

                // verify if inputted password matches saved password
                if(password_verify($password, $savedPassword))
                {
                    $secret_key = $_ENV['SECRET_KEY'];
                    $issuer_claim = $_ENV['ISSUER_CLAIM'];;
                    $audience_claim = $_ENV['AUDIENCE_CLAIM'];
                    $issued_at = time(); // issued at
                    $notbefore_claim = $issued_at + 10; //not before in seconds
                    $expire_claim = $issued_at + 60 * 60; // expire time in seconds
                    $token = array(
                        "iss" => $issuer_claim,
                        "aud" => $audience_claim,
                        "iat" => $issued_at,
                        "nbf" => $notbefore_claim,
                        "exp" => $expire_claim,
                        "data" => array(
                            "firstname" => $firstname,
                            "lastname" => $lastname,
                            "email" => $email
                        ),
                        'user' => $user
                    );
                    http_response_code(200);

                    // save user login timestamp
                    $capsule->table('loginRecords')
                        ->insert([
                            'user_id' => $user_id,
                            'created_at' => date("Y-m-d H:i:s")
                        ]);

                    $jwt = JWT::encode($token['user'], $secret_key, $_ENV['JWT_ALG']);
                    echo json_encode(
                        array(
                            "message" => "Successful login.",
                            "jwt" => $jwt,
                            "email" => $email,
                            "expireAt" => $expire_claim,

                        ));
                }
                else{

                    http_response_code(401);
                    echo json_encode(array("message" => "Login failed.", "password" => $password));
                }

            }
        }
    }
}
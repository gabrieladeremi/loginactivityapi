<?php

namespace App\services;

use Carbon\Carbon;
use Firebase\JWT\JWT;
use Illuminate\Database\Capsule\Manager;

class LoginService
{
    public static function loginUser(Array $request, Manager $connection)
    {
        $email = $request['email'];

        $password = $request['password'];

        // retrieve user details from the Database querying against user email
        $user = $connection->table('users')
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
                    $not_before_claim = $issued_at + 10; //not before in seconds
                    $expire_claim = $issued_at; // expire time in seconds
                    $token = array(
                        "iss" => $issuer_claim,
                        "aud" => $audience_claim,
                        "iat" => $issued_at,
                        "nbf" => $not_before_claim,
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
                    $connection->table('loginRecords')
                        ->insert([
                            'user_id' => $user_id,
                            'created_at' => date("Y-m-d H:i:s"),
                            'last_seen' => Carbon::now()
                        ]);

                    $jwt = JWT::encode($token['user'], $secret_key, $_ENV['JWT_ALG']);
                    return json_encode(
                        array(
                            "message" => "Successful login.",
                            "jwt" => $jwt,
                            "email" => $email,
                            "expireAt" => $expire_claim,

                        ));
                }
                else{

                    http_response_code(401);
                    return json_encode(array("message" => "Login failed.", "password" => $password));
                }

            }
        }
    }
}
<?php

namespace App\Services;

use App\Database\Database;
use App\Exceptions\LoginFailedException;
use Carbon\Carbon;
use App\Helper\TokenGenerator;


class LoginService
{
    public static function loginUser(
        string $email,
        string $password
    ): array {

        $connection = (new Database())->getDatabaseConnection();

        // retrieve user details from the Database querying against user email
        $user = (array) $connection->table('users')
            ->where('email' , $email)
            ->get([
                'id',
                'firstname',
                'lastname',
                'password'
            ])->first();


        if(!password_verify($password, $user['password'])  ||  $user == null  ){

            throw LoginFailedException::noUserFound('No user found with the credentials');
        }

        $generatedToken = TokenGenerator::generateToken();

        if(! $generatedToken){

            throw LoginFailedException::tokenNotGenerated('Token not generated');

        }

        $connection->table('loginRecords')
            ->insert([
                'user_id' => $user['id'],
                'created_at' => date("Y-m-d H:i:s"),
                'last_seen' => Carbon::now()
            ]);

        array_push($generatedToken, $user);

        return $generatedToken;

    }

}
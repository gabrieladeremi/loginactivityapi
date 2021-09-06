<?php

namespace App\controllers;

use App\Database\Database;
use Carbon\Carbon;
use Firebase\JWT\JWT;
use League\Fractal\Resource\Collection;

class LogoutController
{
    public function post()
    {
        $user_id = null;

        var_dump($_SERVER['HTTP_AUTHORIZATION']);

        $token = str_replace("Bearer ", "", $_SERVER['HTTP_AUTHORIZATION']);

        try{
            $decodedToken = JWT::decode($token, $_ENV['SECRET_KEY'], [$_ENV['JWT_ALG']]);

            $castedToken = (array) $decodedToken;

            var_dump($decodedToken);

            $user_id = $castedToken[0]->id;

            echo $user_id;

        } catch (\Exception $e) {
            if($e->getMessage() == "Expired Token"){

                echo 'Expired token';

                $connection = (new Database())->getDatabaseConnection();

                echo $user_id;

                $connection->table('loginRecords')
                    ->where('user_id', $user_id)
                    ->insert([
                        'last_seen' => Carbon::now()
                    ]);
            }
            echo 'could not decode' . $e;
        }
    }
}
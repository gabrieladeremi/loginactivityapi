<?php

namespace App\controllers;

use App\Database\Database;
use App\transformers\LoginRecordTransformer;
use Firebase\JWT\JWT;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;
use mysql_xdevapi\Exception;


class LoginRecordController
{
    /**
     * @var Manager
     */
    private $fractal;

    /**
     * @var UserTransformer
     */
    private $loginRecordTransformer;


    public function get()
    {
        $this->fractal = new Manager();
        $this->loginRecordTransformer = new LoginRecordTransformer();

        $token = str_replace("Bearer ", "", $_SERVER['HTTP_AUTHORIZATION']);

        try{
            $decodedToken = JWT::decode($token, $_ENV['SECRET_KEY'], [$_ENV['JWT_ALG']]);

            $coercedToken = (array) $decodedToken;

            $user_id = $coercedToken[0]->id;

            $capsule = (new Database())->getDatabaseConnection();

            $loginRecords = $capsule->table('loginrecords')
                ->where('user_id', $user_id)
                ->get([
                    'user_id',
                    'created_at',
                    'updated_at',
                ]);

            if(count($loginRecords) > 0)
            {
                foreach ($loginRecords as $record){
                    $transformedRecord = new Collection($record, $this->loginRecordTransformer);
                    $transformedRecord = $this->fractal->createData($transformedRecord);

                    echo (json_encode([
                        'status' => 200,
                        'data' => $transformedRecord->getResource()->getData()
                    ]));
                }
            }

        } catch (\Exception $e) {
            echo 'could not decode' . $e;
        }

    }
}
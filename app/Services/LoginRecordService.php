<?php

namespace App\services;

use App\Database\Database;
use App\transformers\LoginRecordTransformer;
use Firebase\JWT\JWT;
use League\Fractal\Manager;
use League\Fractal\Resource\Collection;

class LoginRecordService
{
    public static function getUserLoginRecords($token)
    {
        $fractal = new Manager();

        $loginRecordTransformer = new LoginRecordTransformer();

        try{
            $decodedToken = JWT::decode($token, $_ENV['SECRET_KEY'], [$_ENV['JWT_ALG']]);

            $castedToken = (array) $decodedToken;

            $user_id = $castedToken[0]->id;

            $capsule = (new Database())->getDatabaseConnection();

            $loginRecords = $capsule->table('loginRecords')
                ->where('user_id', $user_id)
                ->get([
                    'user_id',
                    'created_at',
                    'updated_at',
                    'last_seen'
                ]);

            if(count($loginRecords) > 0)
            {
                foreach ($loginRecords as $record){
                    $transformedRecord = new Collection($record, $loginRecordTransformer);

                    $transformedRecord = $fractal->createData($transformedRecord);

                    return (json_encode([
                        'status' => 200,
                        'data' => $transformedRecord->getResource()->getData()
                    ]));
                }
            }

        } catch (\Exception $e) {
            return 'could not decode' . $e;
        }
    }

}
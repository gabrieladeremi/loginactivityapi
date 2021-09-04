<?php

namespace App\controllers;

use App\services\LoginRecordService;


class LoginRecordController
{
    public function get()
    {
        $token = str_replace("Bearer ", "", $_SERVER['HTTP_AUTHORIZATION']);

       echo json_encode([
           'data' => LoginRecordService::getUserLoginRecords($token)
       ]);

    }
}
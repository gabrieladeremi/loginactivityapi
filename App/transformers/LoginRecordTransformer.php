<?php

namespace App\transformers;

use App\model\LoginRecord;
use League\Fractal\TransformerAbstract;

class LoginRecordTransformer
{
    public function loginRecordTransformer(LoginRecord $loginRecord)
    {
        return [
            'firstname' => $loginRecord->user_id,

        ];
    }
}
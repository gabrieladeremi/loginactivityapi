<?php

namespace App\transformers;

use App\model\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    public function transform(User $user)
    {
        return [
          'firstname' => $user->firstname,
          'lastname' => $user->lastname,
          'email' => $user->email,
          'phone_number' => $user->phone_number,
          'address' => $user->address
        ];
    }
}
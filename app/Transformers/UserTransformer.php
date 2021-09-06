<?php

namespace App\transformers;

use App\Model\User;
use League\Fractal\TransformerAbstract;

class UserTransformer extends TransformerAbstract
{

    public function transform(User $user): array
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
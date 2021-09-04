<?php

namespace App\model;

use Illuminate\Database\Eloquent\Model as Eloquent;

class LoginRecord extends Eloquent
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'user_id', 'email', 'password'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [

        'remember_token',

    ];


}
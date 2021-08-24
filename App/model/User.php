<?php
namespace App\model;
use Illuminate\Database\Eloquent\Model as Eloquent;

class User extends Eloquent

{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'address',
        'phone_number'

    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */

    protected $hidden = [

        'password', 'remember_token',

    ];

}
<?php

namespace App\Services;

use App\Database\Database;
use App\Exceptions\RegistrationFailedException;
use App\Model\User;

class RegistrationService
{
    public static function registerUser(
        string $firstname,
        string $lastname,
        string $email,
        string $password,
        string $phoneNumber,
        ?string $address = null
    ): User {
        $capsule = (new Database())->getDatabaseConnection();

        $hashPassword = password_hash($password, PASSWORD_DEFAULT);

        $wasUserCreated = $capsule->table('users')->insert([
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
            'phone_number' => $phoneNumber,
            'address' => $address,
            'password' => $hashPassword,
            'created_at' => date('Y-m-d H:i:s')
        ]);

        if (! $wasUserCreated) {

            throw RegistrationFailedException::failed('Could not create user profile');

        }

        /** @var User|null $user */
        $user = User::where('email', $email)->first();

        if ($user === null) {

            throw new \RuntimeException('Issues retrieving your profile information');

        }

        return $user;
    }

}
<?php

namespace App\Helper;

use Firebase\JWT\JWT;

class TokenGenerator
{
    public static function generateToken(): array
    {
        $secret_key = $_ENV['SECRET_KEY'];
        $issuer_claim = $_ENV['ISSUER_CLAIM'];;
        $audience_claim = $_ENV['AUDIENCE_CLAIM'];
        $issued_at = time(); // issued at
        $not_before_claim = $issued_at + 10; //not before in seconds
        $expire_claim = 60; // expire time in seconds

        $token = [
            "iss" => $issuer_claim,
            "aud" => $audience_claim,
            "iat" => $issued_at,
            "nbf" => $not_before_claim,
            "exp" => $expire_claim,
        ];

        $jwt = JWT::encode($token, $secret_key, $_ENV['JWT_ALG']);

        return [
                "message" => "Successful login.",
                "jwt" => $jwt,
                "expireAt" => $expire_claim,
        ];
    }
}
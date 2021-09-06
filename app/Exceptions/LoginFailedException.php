<?php

namespace App\Exceptions;

use JetBrains\PhpStorm\Pure;

class LoginFailedException extends \RuntimeException
{

    #[Pure] public static function noUserFound(string $message): static
    {
        return new static($message, 404);
    }

    #[Pure] public static function tokenNotGenerated(string $message): static
    {
        return new static($message, 500);
    }
}
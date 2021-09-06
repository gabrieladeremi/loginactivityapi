<?php
namespace App\Exceptions;

use JetBrains\PhpStorm\Pure;

class RegistrationFailedException extends \RuntimeException
{
    #[Pure]
    public static function failed(string $message): static
    {
        return new static($message, 500);
    }
}
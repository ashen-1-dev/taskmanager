<?php

namespace App\Domain\ValueObject\User;

use InvalidArgumentException;

class Email
{
    public function __construct(
        private readonly string $email
    ) {
        if (filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Неверный email');
        }
    }
}
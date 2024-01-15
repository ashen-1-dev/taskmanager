<?php

namespace App\Domain\ValueObject\User;

use Doctrine\DBAL\Types\Types;
use InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Email
{
    public function __construct(
        #[ORM\Column(type: Types::STRING, unique: true)]
        private readonly string $email
    ) {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Неверный email');
        }
    }

    public function toString(): string
    {
        return $this->email;
    }
}
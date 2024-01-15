<?php

namespace App\Domain\ValueObject\User;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Password
{
    public function __construct(
        #[ORM\Column(type: Types::STRING)]
        private readonly string $password
    ) {
    }

    public function toString(): string
    {
        return $this->password;
    }
}
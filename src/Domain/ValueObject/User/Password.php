<?php

namespace App\Domain\ValueObject\User;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Mapping\ClassMetadata;

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

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('password', new Assert\Length(min: 6, max: 100));
    }
}
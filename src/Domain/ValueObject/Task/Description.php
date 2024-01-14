<?php

namespace App\Domain\ValueObject\Task;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Embeddable]
class Description
{
    private const MIN_DESCRIPTION_SIZE = 1;

    private const MAX_DESCRIPTION_SIZE = 1000;

    public function __construct(
        #[ORM\Column(type: Types::TEXT)]
        private readonly string $description
    ) {
    }

    public function getText(): string
    {
        return $this->description;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint(
            'description',
            new Assert\Length(min: self::MIN_DESCRIPTION_SIZE, max: self::MAX_DESCRIPTION_SIZE)
        );
    }
}
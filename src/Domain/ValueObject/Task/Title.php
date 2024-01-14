<?php

namespace App\Domain\ValueObject\Task;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Mapping\ClassMetadata;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Embeddable]
class Title
{
    private const MIN_TITLE_SIZE = 1;
    private const MAX_TITLE_SIZE = 100;

    public function __construct(
        #[ORM\Column(type: Types::STRING)]
        private readonly string $title
    )
    {}

    public function getText(): string
    {
        return $this->title;
    }

    public static function loadValidatorMetadata(ClassMetadata $metadata): void
    {
        $metadata->addPropertyConstraint('title', new Assert\NotBlank());
        $metadata->addPropertyConstraint('title', new Assert\Length(min: self::MIN_TITLE_SIZE, max: self::MAX_TITLE_SIZE));
    }
}
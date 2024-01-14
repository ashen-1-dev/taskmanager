<?php

namespace App\Domain\ValueObject\Task;

use Doctrine\DBAL\Types\Types;
use InvalidArgumentException;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Embeddable]
class Title
{
    private const MIN_TITLE_SIZE = 5;
    private const MAX_TITLE_SIZE = 100;

    public function __construct(
        #[ORM\Column(type: Types::STRING)]
        private readonly string $title
    )
    {
        $size = mb_strlen($title);
        if ($size > self::MAX_TITLE_SIZE && $size < self::MIN_TITLE_SIZE) {
            throw new InvalidArgumentException(
                'Длина текста должна быть от ' . self::MIN_TITLE_SIZE . ' до ' . self::MAX_TITLE_SIZE . ' символов'
            );
        }
    }

    public function getText(): string
    {
        return $this->title;
    }
}
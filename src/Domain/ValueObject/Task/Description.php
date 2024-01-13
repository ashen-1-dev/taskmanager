<?php

namespace App\Domain\ValueObject\Task;

use InvalidArgumentException;

class Description
{
    private const MIN_TEXT_SIZE = 5;

    private const MAX_TEXT_SIZE = 1000;

    public function __construct(
        private readonly string $description
    ) {
        $size = mb_strlen($this->description);
        if ($size > self::MAX_TEXT_SIZE || $size < self::MIN_TEXT_SIZE) {
            throw new InvalidArgumentException(
                'Длина текста должна быть от ' . self::MIN_TEXT_SIZE . ' до ' . self::MAX_TEXT_SIZE . ' символов'
            );
        }
    }

    public function getText(): string
    {
        return $this->description;
    }
}
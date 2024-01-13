<?php

namespace App\Domain\ValueObject;

use Symfony\Component\Uid\Uuid;

class ID
{
    public function __construct(
        private readonly Uuid $uuid
    ) {
    }

    public static function generate(): ID
    {
        return new ID(Uuid::v4());
    }
}
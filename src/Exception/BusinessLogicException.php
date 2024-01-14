<?php

namespace App\Exception;

class BusinessLogicException extends \RuntimeException
{
    private readonly string $userMessage;

    public function __construct(string $userMessage)
    {
        parent::__construct('Business exception');
        $this->userMessage = $userMessage;
    }

    public function getUserMessage(): string
    {
        return $this->userMessage;
    }
}
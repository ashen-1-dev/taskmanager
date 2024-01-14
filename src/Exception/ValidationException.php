<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolationList;

class ValidationException extends \RuntimeException
{
    private readonly ConstraintViolationList $errors;

    public function __construct($errors)
    {
        $this->errors = $errors;
        parent::__construct('Validation Exception', 422);
    }

    public static function withMessages(ConstraintViolationList $errors): static
    {
        return new static($errors);
    }

    public function getErrors(): ConstraintViolationList
    {
        return $this->errors;
    }
}
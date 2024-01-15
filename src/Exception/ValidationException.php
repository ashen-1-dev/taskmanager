<?php

namespace App\Exception;

use Symfony\Component\Validator\ConstraintViolation;
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

    public static function withRawMessages(array $errors): static
    {
        $list = new ConstraintViolationList();
        foreach ($errors as $name => $messages) {
            $messages = is_string($messages) ? [$messages] : $messages;
            foreach ($messages as $message) {
                $violation = new ConstraintViolation($message, null, [], null, $name, null);
                $list->add($violation);
            }
        }
        return self::withMessages($list);
    }

    public function getErrors(): ConstraintViolationList
    {
        return $this->errors;
    }
}
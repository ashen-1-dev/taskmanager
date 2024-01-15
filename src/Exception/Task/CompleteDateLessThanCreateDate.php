<?php

namespace App\Exception\Task;

use App\Exception\BusinessLogicException;

class CompleteDateLessThanCreateDate extends BusinessLogicException
{
    public function __construct()
    {
        parent::__construct('Complete date cannot be less than creation date');
    }
}
<?php

namespace App\Exception\Task;

use App\Exception\BusinessLogicException;

class CompleteDateBiggerThanCreateDate extends BusinessLogicException
{
    public function __construct()
    {
        parent::__construct('Complete date cannot be bigger than creation date');
    }
}
<?php

namespace App\Exception\Task;

use App\Exception\BusinessLogicException;

class WrongTaskCompleteDate extends BusinessLogicException
{
    public function __construct()
    {
        parent::__construct('Wrong task complete date');
    }
}
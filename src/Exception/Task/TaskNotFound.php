<?php

namespace App\Exception\Task;

use App\Exception\BusinessLogicException;

class TaskNotFound extends BusinessLogicException
{
    public function __construct()
    {
        parent::__construct('Task not found');
    }
}
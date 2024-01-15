<?php

namespace App\Domain\ValueObject\Task;

enum TaskStatus: string
{
    case Completed = 'completed';

    case NotCompleted = 'not-completed';
}
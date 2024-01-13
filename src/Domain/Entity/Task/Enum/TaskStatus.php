<?php

namespace App\Domain\Entity\Task\Enum;

enum TaskStatus: string
{
    case Completed = 'completed';

    case NotCompleted = 'not-completed';
}
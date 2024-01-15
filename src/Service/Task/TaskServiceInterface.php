<?php

namespace App\Service\Task;

use App\Domain\Entity\Task\Task;
use App\Domain\Entity\User\User;
use App\Domain\ValueObject\Task\Description;
use App\Domain\ValueObject\Task\Title;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\Collection;

interface TaskServiceInterface
{
    /** @return Collection<int, Task> */
    public function getUserTasks(User $user): Collection;

    public function createTask(
        User $user,
        Title $title,
        Description $description,
        ?CarbonInterface $completedAt = null
    ): Task;

    public function editTask(
        Task $task,
        Title $title,
        Description $description,
        ?CarbonInterface $completedAt = null
    ): Task;

    public function deleteTask(Task $task): true;

    public function markTaskAsComplete(Task $task): true;
}
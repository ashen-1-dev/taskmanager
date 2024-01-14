<?php

namespace App\Service\Task;

use App\Domain\Entity\Task\Task;
use App\Domain\ValueObject\Task\Description;
use App\Domain\ValueObject\Task\Title;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Uid\AbstractUid;

interface TaskServiceInterface
{
    /** @return Collection<int, Task> */
    public function getUserTasks(AbstractUid $userId): Collection;

    public function createTask(
        AbstractUid $userId,
        Title $title,
        Description $description,
        ?CarbonInterface $completedAt = null
    ): Task;

    public function editTask(
        AbstractUid $taskId,
        Title $title,
        Description $description,
        ?CarbonInterface $completedAt = null
    ): Task;

    public function deleteTask(AbstractUid $taskId): true;

    public function markTaskAsComplete(AbstractUid $taskId): true;
}
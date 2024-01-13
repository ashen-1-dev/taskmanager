<?php

namespace App\Domain\Entity\Task;

use App\Domain\Entity\Task\Enum\TaskStatus;
use App\Domain\ValueObject\ID;
use App\Domain\ValueObject\Task\Description;
use App\Domain\ValueObject\Task\Title;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use InvalidArgumentException;

class Task
{
    private ID $id;
    private Title $title;
    private Description $description;
    private CarbonInterface $createdAt;
    private ?CarbonInterface $completedAt;

    private function __construct(
        ID $id,
        Title $title,
        Description $description,
        Carbon $createdAt,
        ?Carbon $completedAt = null
    ) {
        if (!is_null($completedAt) && $completedAt > $createdAt) {
            throw new InvalidArgumentException('Дата выполнения не должна превышать даты создания задачи');
        }

        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->completedAt = $completedAt;
    }

    public static function createTask(
        ID $id,
        Title $title,
        Description $description,
        Carbon $createdAt,
        ?Carbon $completedAt = null
    ): Task {
        return new Task(
            id: $id,
            title: $title,
            description: $description,
            createdAt: $createdAt,
            completedAt: $completedAt
        );
    }

    public function markAsCompleted(CarbonInterface $completedAt): true
    {
        if ($completedAt->isFuture()) {
            throw new InvalidArgumentException('Неверная дата выполнения задачи');
        }

        $this->completedAt = $completedAt;

        return true;
    }

    public function editTask(
        Title $title,
        Description $description,
        ?CarbonInterface $completedAt
    ): Task {
        if (!is_null($completedAt) && $completedAt > $this->createdAt) {
            throw new InvalidArgumentException('Дата выполнения не должна превышать даты создания задачи');
        }

        $this->title = $title;
        $this->description = $description;
        $this->completedAt = $completedAt;

        return $this;
    }

    // GETTERS
    public function getId(): ID
    {
        return $this->id;
    }

    public function getTitle(): Title
    {
        return $this->title;
    }

    public function getDescription(): Description
    {
        return $this->description;
    }

    public function getCreatedAt(): CarbonInterface
    {
        return $this->createdAt;
    }

    public function getCompletedAt(): CarbonInterface
    {
        return $this->completedAt;
    }

    public function getStatus(): TaskStatus
    {
        return !is_null($this->completedAt) ? TaskStatus::Completed : TaskStatus::NotCompleted;
    }
}
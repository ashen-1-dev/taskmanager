<?php

namespace App\Service\Task;

use App\Domain\Entity\Task\Task;
use App\Domain\Entity\User\User;
use App\Domain\ValueObject\Task\Description;
use App\Domain\ValueObject\Task\Title;
use App\Exception\User\UserNotFound;
use App\Exception\ValidationException;
use App\Repository\Task\TaskRepository;
use App\Repository\User\UserRepository;
use Carbon\CarbonImmutable;
use Carbon\CarbonInterface;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Uid\AbstractUid;
use Symfony\Component\Uid\Uuid;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TaskService implements TaskServiceInterface
{
    public function __construct(
        private readonly TaskRepository $taskRepository,
        private readonly UserRepository $userRepository,
        private readonly EntityManagerInterface $entityManager,
        private readonly ValidatorInterface $validator
    ) {
    }

    /** @inheritDoc */
    public function getUserTasks(AbstractUid $userId): Collection
    {
        $tasks = $this->taskRepository->getUsersTask($userId);

        return $tasks;
    }

    public function createTask(
        AbstractUid $userId,
        Title $title,
        Description $description,
        ?CarbonInterface $completedAt = null
    ): Task {
        /** @var User $user */
        $user = $this->userRepository->findOneBy(['id' => $userId]);

        if (!$user) {
            throw new UserNotFound();
        }

        $task = $user->createTask(
            id: Uuid::v4(),
            title: $title,
            description: $description,
            createdAt: CarbonImmutable::now(),
            completedAt: $completedAt
        );

        $this->validateTask($task);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function editTask(
        Task $task,
        Title $title,
        Description $description,
        ?CarbonInterface $completedAt = null
    ): Task {
        $task->editTask(
            title: $title,
            description: $description,
            completedAt: $completedAt
        );

        $this->validateTask($task);

        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    public function deleteTask(Task $task): true
    {
        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return true;
    }

    public function markTaskAsComplete(Task $task): true
    {
        $task->markAsCompleted(CarbonImmutable::now());
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return true;
    }

    protected function validateTask(Task $task): void
    {
        $errors = $this->validator->validate($task);
        if ($errors->count() > 0) {
            throw ValidationException::withMessages($errors);
        }
    }
}
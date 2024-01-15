<?php

namespace App\Controller\Task;

use App\Controller\APIController;
use App\Domain\Entity\Task\Task;
use App\Domain\Entity\User\User;
use App\Domain\ValueObject\Task\Description;
use App\Domain\ValueObject\Task\Title;
use App\Security\Task\TaskVoter;
use App\Service\Task\TaskServiceInterface;
use Carbon\CarbonImmutable;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class TaskController extends APIController
{
    #[Route('/tasks', name: 'get_user_tasks', methods: Request::METHOD_GET)]
    public function getUserTasks(
        #[CurrentUser]
        User $user,
        TaskServiceInterface $taskService
    ): JsonResponse {
        $tasks = $taskService->getUserTasks($user);

        return $this->responseOK($tasks->map(fn(Task $task) => $this->taskToArray($task)));
    }

    #[Route('/tasks', name: 'create_task', methods: Request::METHOD_POST)]
    public function createTask(
        #[CurrentUser]
        User $user,
        TaskServiceInterface $taskService,
        Request $request
    ): JsonResponse {
        $payload = $request->getPayload();

        $title = new Title($payload->get('title'));
        $description = new Description($payload->get('description'));
        $completedAt = !is_null($payload->has('completedAt'))
            ? CarbonImmutable::createFromFormat(
                'c',
                $payload->get('completedAt')
            )
            : null;

        $task = $taskService->createTask(
            user: $user,
            title: $title,
            description: $description,
            completedAt: $completedAt
        );

        return $this->responseCreated($this->taskToArray($task));
    }

    #[Route('/tasks/{task}', name: 'edit_task', methods: Request::METHOD_PUT)]
    #[IsGranted(TaskVoter::EDIT, 'task')]
    public function editTask(
        TaskServiceInterface $taskService,
        Request $request,
        #[MapEntity()]
        Task $task
    ): JsonResponse {
        $payload = $request->getPayload();

        $title = new Title($payload->get('title'));
        $description = new Description($payload->get('description'));
        $completedAt = !is_null($payload->has('completedAt'))
            ? CarbonImmutable::createFromFormat(
                'Y-m-d H:i:s',
                $payload->get('completedAt')
            )
            : null;

        $task = $taskService->editTask(
            $task,
            title: $title,
            description: $description,
            completedAt: $completedAt
        );

        return $this->responseOK($this->taskToArray($task));
    }

    #[Route('/tasks/{task}', name: 'delete_task', methods: Request::METHOD_DELETE)]
    #[IsGranted(TaskVoter::DELETE, 'task')]
    public function deleteTask(
        TaskServiceInterface $taskService,
        #[MapEntity]
        Task $task
    ): JsonResponse {
        $taskService->deleteTask($task);
        return $this->responseOK();
    }

    #[Route('/tasks/{task}/complete', name: 'mark_task_completed', methods: Request::METHOD_POST)]
    #[IsGranted(TaskVoter::EDIT, 'task')]
    public function markTaskAsCompleted(
        TaskServiceInterface $taskService,
        #[MapEntity]
        Task $task
    ): JsonResponse {
        $taskService->markTaskAsComplete($task);
        return $this->responseOK();
    }

    private function taskToArray(Task $task): array
    {
        return [
            'id' => $task->getId()->toRfc4122(),
            'title' => $task->getTitle()->getText(),
            'description' => $task->getDescription()->getText(),
            'status' => $task->getStatus()->value,
            'completedAt' => $task->getCompletedAt(),
            'createdAT' => $task->getCreatedAt()
        ];
    }
}
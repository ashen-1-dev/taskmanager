<?php

namespace App\Controller\Task;

use App\Domain\ValueObject\Task\Description;
use App\Domain\ValueObject\Task\Title;
use App\Service\Task\TaskService;
use App\Service\Task\TaskServiceInterface;
use Carbon\CarbonImmutable;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Uid\UuidV4;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'get_user_tasks', methods: Request::METHOD_GET)]
    public function getUserTasks(TaskServiceInterface $taskService): JsonResponse
    {
        //temporary stub
        $userId = new UuidV4('2b06804f-2004-43e9-ba71-611be7d84b4e');
        $tasks = $taskService->getUserTasks($userId);

        $result = [];

        foreach ($tasks as $key => $task) {
            $result[$key]['id'] = $task->getId()->toRfc4122();
            $result[$key]['title'] = $task->getTitle()->getText();
            $result[$key]['description'] = $task->getDescription()->getText();
            $result[$key]['status'] = $task->getStatus();
            $result[$key]['completedAt'] = $task->getCompletedAt();
        }

        return new JsonResponse($result);
    }

    #[Route('/tasks', name: 'create_task', methods: Request::METHOD_POST)]
    public function createTask(TaskServiceInterface $taskService, Request $request): JsonResponse
    {
        $userId = new UuidV4('2b06804f-2004-43e9-ba71-611be7d84b4e');

        $payload = $request->getPayload();

        $title = new Title($payload->get('title'));
        $description = new Description($payload->get('description'));
        $completedAt = !is_null($payload->has('completedAt'))
            ? CarbonImmutable::createFromFormat(
                'Y-m-d H:i:s',
                $payload->get('completedAt')
            )
            : null;

        $task = $taskService->createTask(
            userId: $userId,
            title: $title,
            description: $description,
            completedAt: $completedAt
        );

        return new JsonResponse(
            [
                'id' => $task->getId()->toRfc4122(),
                'title' => $task->getTitle()->getText(),
                'description' => $task->getDescription()->getText()
            ], Response::HTTP_CREATED
        );
    }

    #[Route('/tasks/{taskId}', name: 'edit_task', methods: Request::METHOD_PUT)]
    public function editTask(TaskServiceInterface $taskService, Request $request, string $taskId): JsonResponse
    {
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
            UuidV4::fromString($taskId),
            title: $title,
            description: $description,
            completedAt: $completedAt
        );

        return new JsonResponse(
            [
                'id' => $task->getId()->toRfc4122(),
                'title' => $task->getTitle()->getText(),
                'description' => $task->getDescription()->getText()
            ],
            Response::HTTP_OK
        );
    }

    #[Route('/tasks/{taskId}', name: 'delete_task', methods: Request::METHOD_DELETE)]
    public function deleteTask(TaskServiceInterface $taskService, string $taskId): JsonResponse
    {
        $taskService->deleteTask(UuidV4::fromString($taskId));
        return new JsonResponse();
    }

    #[Route('/tasks/{taskId}/complete', name: 'mark_task_completed', methods: Request::METHOD_POST)]
    public function markTaskAsCompleted(TaskServiceInterface $taskService, string $taskId): JsonResponse
    {
        $taskService->markTaskAsComplete(UuidV4::fromString($taskId));
        return new JsonResponse();
    }
}
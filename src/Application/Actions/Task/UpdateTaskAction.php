<?php
declare(strict_types=1);

namespace App\Application\Actions\Task;

use App\Domain\Task\Entity\Task;
use App\Domain\Shared\Exception\InvalidTimestampException;
use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;

class UpdateTaskAction extends TaskAction
{
    /**
     * {@inheritdoc}
     * @throws InvalidTimestampException
     */
    protected function action(): Response
    {
        $taskId = (string) $this->resolveArg('id');
        $data = (array) $this->request->getParsedBody();

        $task = $this->taskRepository->findTaskOfId($taskId);

        $title = $data["title"] ?? '';
        $description = $data["description"] ?? '';

        $entity = Task::create($title, $description, $task->getId(), $task->getDateCreated());

        $task = $this->taskService->update($entity);

        $this->logger->info("Task of id `{$task->getId()}` was update.");

        return $this->respondWithData($task, StatusCodeInterface::STATUS_OK);
    }
}

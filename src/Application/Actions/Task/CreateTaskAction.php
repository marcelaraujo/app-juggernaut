<?php
declare(strict_types=1);

namespace App\Application\Actions\Task;

use App\Domain\Task\Entity\Task;
use App\Domain\Shared\Exception\InvalidTimestampException;
use Psr\Http\Message\ResponseInterface as Response;
use Fig\Http\Message\StatusCodeInterface;

class CreateTaskAction extends TaskAction
{
    /**
     * {@inheritdoc}
     * @throws InvalidTimestampException
     */
    protected function action(): Response
    {
        $data = (array) $this->request->getParsedBody();

        $title = $data["title"] ?? '';
        $description = $data["description"] ?? '';

        $entity = Task::create($title, $description);

        $task = $this->taskService->create($entity);

        $this->logger->info("Task of id `{$task->getId()}` was created.");

        return $this->respondWithData($task, StatusCodeInterface::STATUS_CREATED);
    }
}

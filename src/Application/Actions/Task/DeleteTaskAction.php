<?php
declare(strict_types=1);

namespace App\Application\Actions\Task;

use Fig\Http\Message\StatusCodeInterface;
use Psr\Http\Message\ResponseInterface as Response;

class DeleteTaskAction extends TaskAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $taskId = (string) $this->resolveArg('id');

        $this->taskService->delete($taskId);

        $this->logger->info("Task of id `{$taskId}` was delete.");

        return $this->respondWithData(null, StatusCodeInterface::STATUS_NO_CONTENT);
    }
}

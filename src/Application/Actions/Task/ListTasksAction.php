<?php
declare(strict_types=1);

namespace App\Application\Actions\Task;

use Psr\Http\Message\ResponseInterface as Response;

class ListTasksAction extends TaskAction
{
    /**
     * {@inheritdoc}
     */
    protected function action(): Response
    {
        $tasks = $this->taskRepository->findAll();

        $this->logger->info("Tasks list was viewed.");

        return $this->respondWithData($tasks);
    }
}

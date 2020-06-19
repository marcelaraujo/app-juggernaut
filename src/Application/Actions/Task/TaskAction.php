<?php
declare(strict_types=1);

namespace App\Application\Actions\Task;

use App\Application\Actions\Action;
use App\Domain\Task\Repository\TaskRepositoryInterface;
use App\Domain\Task\Service\TaskServiceInterface;
use Psr\Log\LoggerInterface;

abstract class TaskAction extends Action
{
    /**
     * @var TaskRepositoryInterface
     */
    protected TaskRepositoryInterface $taskRepository;

    /**
     * @var TaskServiceInterface
     */
    protected TaskServiceInterface $taskService;

    /**
     * @param LoggerInterface $logger
     * @param TaskRepositoryInterface $taskRepository
     * @param TaskServiceInterface $taskService
     */
    public function __construct(LoggerInterface $logger, TaskRepositoryInterface $taskRepository, TaskServiceInterface $taskService)
    {
        parent::__construct($logger);
        $this->taskRepository = $taskRepository;
        $this->taskService = $taskService;
    }
}

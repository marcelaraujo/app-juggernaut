<?php
declare(strict_types=1);

namespace App\Domain\Task\Service;

use App\Domain\Shared\Entity\EntityInterface;
use App\Domain\Task\Entity\Task;
use App\Domain\Task\Repository\TaskRepositoryInterface;

class TaskService implements TaskServiceInterface
{
    /**
     * @var TaskRepositoryInterface
     */
    private TaskRepositoryInterface $repository;

    /**
     * ServiceInterface constructor.
     * @param TaskRepositoryInterface $repository
     */
    public function __construct(TaskRepositoryInterface $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @param EntityInterface $entity
     * @return Task
     */
    public function create(EntityInterface $entity): Task
    {
        return $this->repository->insert($entity);
    }

    /**
     * @param EntityInterface $entity
     * @return Task
     */
    public function update(EntityInterface $entity): Task
    {
        return $this->repository->update($entity);
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id): bool
    {
        return $this->repository->delete($id);
    }
}
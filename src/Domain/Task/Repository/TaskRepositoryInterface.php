<?php
declare(strict_types=1);

namespace App\Domain\Task\Repository;

use App\Domain\Shared\Repository\RepositoryInterface;
use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Task\Entity\Task;
use App\Domain\Task\Exception\TaskNotFoundException;

interface TaskRepositoryInterface extends RepositoryInterface
{
    /**
     * @return Task[]
     */
    public function findAll(): array;

    /**
     * @param string $id
     * @return Task
     * @throws TaskNotFoundException
     */
    public function findTaskOfId(string $id): Task;

    /**
     * @param Task $task
     * @return Task
     */
    public function insert(Task $task): Task;

    /**
     * @param Task $task
     * @return Task
     */
    public function update(Task $task): Task;

    /**
     * @param string $id
     * @return bool
     */
    public function delete(string $id): bool;
}

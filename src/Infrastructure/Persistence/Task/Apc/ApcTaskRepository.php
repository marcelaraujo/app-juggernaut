<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Task\Apc;

use App\Domain\Task\Entity\Task;
use App\Domain\Task\Exception\TaskNotFoundException;
use App\Domain\Task\Repository\TaskRepositoryInterface;

class ApcTaskRepository implements TaskRepositoryInterface
{
    /**
     * @var Task[]
     */
    private array $tasks = [];

    /**
     * ApcTaskRepository constructor.
     */
    public function __construct()
    {
        if (!apcu_exists('tasks')) {
            apcu_add('tasks', []);
        }

        $this->tasks = apcu_fetch('tasks');
    }

    /**
     * {@inheritdoc}
     */
    public function findAll(): array
    {
        return array_values($this->tasks);
    }

    /**
     * {@inheritdoc}
     */
    public function findTaskOfId(string $id): Task
    {
        foreach ($this->tasks as $index => $task) {
            if ((string) $task->getId() === $id) {
                return $task;
            }
        }

        throw new TaskNotFoundException();
    }

    /**
     * {@inheritdoc}
     */
    public function insert(Task $task): Task
    {
        $this->tasks[] = $task;

        apcu_store('tasks', $this->tasks);

        return $task;
    }

    /**
     * @inheritDoc
     * @throws TaskNotFoundException
     */
    public function update(Task $task): Task
    {
        $id = (string) $task->getId();

        foreach ($this->tasks as $index => &$current) {
            if ((string) $current->getId() === $id) {
                $current = $task;

                apcu_store('tasks', $this->tasks);

                return $task;
            }
        }

        throw new TaskNotFoundException();
    }

    /**
     * @inheritDoc
     * @throws TaskNotFoundException
     */
    public function delete(string $id): bool
    {
        foreach ($this->tasks as $index => &$current) {
            if ((string) $current->getId() === $id) {
                unset($this->tasks[$index]);

                apcu_store('tasks', $this->tasks);

                return true;
            }
        }

        throw new TaskNotFoundException();
    }
}

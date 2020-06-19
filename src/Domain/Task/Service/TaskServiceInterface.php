<?php
declare(strict_types=1);

namespace App\Domain\Task\Service;

use App\Domain\Shared\Entity\EntityInterface;
use App\Domain\Shared\Service\ServiceInterface;
use App\Domain\Task\Entity\Task;

interface TaskServiceInterface extends ServiceInterface {
    /**
     * @param EntityInterface $entity
     * @return Task
     */
    public function create(EntityInterface $entity): Task;

    /**
     * @param EntityInterface $entity
     * @return Task
     */
    public function update(EntityInterface $entity): Task;

    /**
     * @param string
     * @return bool
     */
    public function delete(string $id): bool;
}
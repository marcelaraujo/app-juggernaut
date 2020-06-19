<?php
declare(strict_types=1);

namespace App\Infrastructure\Persistence\Task\Doctrine\Orm;

use App\Domain\Shared\ValueObject\Uuid;
use App\Domain\Task\Entity\Task;
use App\Domain\Task\Exception\TaskNotFoundException;
use App\Domain\Task\Repository\TaskRepositoryInterface;
use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\EntityManagerInterface;

class DoctrineOrmTaskRepository implements TaskRepositoryInterface
{
    /**
     * @var EntityManagerInterface
     */
    private EntityManagerInterface $entityManager;

    /**
     * @var ObjectRepository|EntityRepository
     */
    private $taskRepository;

    /**
     * DoctrineOrmTaskRepository constructor.
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->taskRepository = $entityManager->getRepository(Task::class);
    }

    /**
     * @inheritDoc
     */
    public function findAll(): array
    {
        return $this->taskRepository->findAll();
    }

    /**
     * @inheritDoc
     */
    public function findTaskOfId(string $id): Task
    {
        return $this->taskRepository->find(Uuid::fromString($id));
    }

    /**
     * @inheritDoc
     */
    public function insert(Task $task): Task
    {
        $this->entityManager->persist($task);
        $this->entityManager->flush();

        return $task;
    }

    /**
     * @inheritDoc
     */
    public function update(Task $task): Task
    {
        $entity = $this->findTaskOfId((string) $task->getId());

        $entity->updateTitle($task->getTitle());
        $entity->updateDescription($task->getDescription());

        $this->entityManager->flush();

        return $entity;
    }

    /**
     * @inheritDoc
     */
    public function delete(string $id): bool
    {
        $task = $this->findTaskOfId($id);

        $this->entityManager->remove($task);
        $this->entityManager->flush();

        return true;
    }
}

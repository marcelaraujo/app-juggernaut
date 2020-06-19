<?php
declare(strict_types=1);

use App\Domain\Task\Entity\Task;
use App\Domain\Task\Repository\TaskRepositoryInterface;
use App\Infrastructure\Persistence\Task\Apc\ApcTaskRepository;
use App\Infrastructure\Persistence\Task\Doctrine\Orm\DoctrineOrmTaskRepository;
use DI\ContainerBuilder;
use Doctrine\ORM\EntityManager;
use Psr\Container\ContainerInterface;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        //TaskRepositoryInterface::class => \DI\autowire(ApcTaskRepository::class),
        TaskRepositoryInterface::class => \DI\autowire(DoctrineOrmTaskRepository::class)
    ]);
};

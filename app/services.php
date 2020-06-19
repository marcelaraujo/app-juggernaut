<?php
declare(strict_types=1);

use App\Domain\Task\Service\TaskService;
use App\Domain\Task\Service\TaskServiceInterface;
use DI\ContainerBuilder;

return function (ContainerBuilder $containerBuilder) {
    // Here we map our UserRepository interface to its in memory implementation
    $containerBuilder->addDefinitions([
        TaskServiceInterface::class => \Di\autowire(TaskService::class),
    ]);
};

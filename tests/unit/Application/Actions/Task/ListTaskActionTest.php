<?php
declare(strict_types=1);

namespace Tests\Unit\Application\Actions\Task;

use App\Application\Actions\ActionPayload;
use App\Domain\Task\Entity\Task;
use App\Domain\Task\Repository\TaskRepositoryInterface;
use DI\Container;
use Tests\Unit\TestCase;

class ListTaskActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $tasks = [
            Task::create("title 01","description 01"),
            Task::create("title 02","description 02"),
        ];

        $taskRepositoryProphecy = $this->prophesize(TaskRepositoryInterface::class);
        $taskRepositoryProphecy
            ->findAll()
            ->willReturn($tasks)
            ->shouldBeCalledOnce();

        $container->set(TaskRepositoryInterface::class, $taskRepositoryProphecy->reveal());

        $request = $this->createRequest('GET', '/tasks');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $tasks);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}

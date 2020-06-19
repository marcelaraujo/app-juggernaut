<?php
declare(strict_types=1);

namespace Tests\Unit\Application\Actions\Task;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Domain\Task\Entity\Task;
use App\Domain\Task\Exception\TaskNotFoundException;
use App\Domain\Task\Repository\TaskRepositoryInterface;
use DI\Container;
use Slim\Middleware\ErrorMiddleware;
use Tests\Unit\TestCase;
use Prophecy\Argument;

class DeleteTaskActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $task = Task::create("title 01","description 01");

        $taskId = (string) $task->getId();

        $taskRepositoryProphecy = $this->prophesize(TaskRepositoryInterface::class);

        $taskRepositoryProphecy
            ->delete($taskId)
            ->willReturn(true)
            ->shouldBeCalledOnce();

        $container->set(TaskRepositoryInterface::class, $taskRepositoryProphecy->reveal());

        $request = $this->createRequest('DELETE', "/tasks/{$taskId}");
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(204, null);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testActionThrowsTaskNotFoundException()
    {
        $app = $this->getAppInstance();

        $callableResolver = $app->getCallableResolver();
        $responseFactory = $app->getResponseFactory();

        $errorHandler = new HttpErrorHandler($callableResolver, $responseFactory);
        $errorMiddleware = new ErrorMiddleware($callableResolver, $responseFactory, true, false ,false);
        $errorMiddleware->setDefaultErrorHandler($errorHandler);

        $app->add($errorMiddleware);

        /** @var Container $container */
        $container = $app->getContainer();

        $taskRepositoryProphecy = $this->prophesize(TaskRepositoryInterface::class);
        $taskRepositoryProphecy
            ->delete(Argument::type("string"))
            ->willThrow(TaskNotFoundException::class)
            ->shouldBeCalledOnce();

        $container->set(TaskRepositoryInterface::class, $taskRepositoryProphecy->reveal());

        $request = $this->createRequest('DELETE', '/tasks/1');
        $response = $app->handle($request);

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'The task you requested does not exist.');
        $expectedPayload = new ActionPayload(404, null, $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}

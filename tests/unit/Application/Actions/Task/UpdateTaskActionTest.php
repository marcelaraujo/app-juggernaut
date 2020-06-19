<?php
declare(strict_types=1);

namespace Tests\Unit\Application\Actions\Task;

use App\Application\Actions\ActionError;
use App\Application\Actions\ActionPayload;
use App\Application\Handlers\HttpErrorHandler;
use App\Domain\Task\Entity\Task;
use App\Domain\Task\Exception\TaskNotFoundException;
use App\Domain\Task\Repository\TaskRepositoryInterface;
use App\Domain\Task\Service\TaskServiceInterface;
use DI\Container;
use Slim\Middleware\ErrorMiddleware;
use Tests\Unit\TestCase;
use Prophecy\Argument;

class UpdateTaskActionTest extends TestCase
{
    public function testAction()
    {
        $app = $this->getAppInstance();

        /** @var Container $container */
        $container = $app->getContainer();

        $task = Task::create("title 01","description 01");

        $taskId = (string) $task->getId();

        $data = [
            "title" => "title 02",
            "description" => "description 02"
        ];

        $taskRepositoryProphecy = $this->prophesize(TaskRepositoryInterface::class);
        $taskRepositoryProphecy
            ->findTaskOfId($taskId)
            ->willReturn($task)
            ->shouldBeCalledOnce();

        $container->set(TaskRepositoryInterface::class, $taskRepositoryProphecy->reveal());

        $taskServiceProphecy = $this->prophesize(TaskServiceInterface::class);
        $taskServiceProphecy
            ->update(Argument::type(Task::class))
            ->willReturn($task)
            ->shouldBeCalledOnce();

        $container->set(TaskServiceInterface::class, $taskServiceProphecy->reveal());

        $request = $this->createRequest('PUT', "/tasks/{$taskId}");

        $response = $app->handle($request->withParsedBody($data));

        $payload = (string) $response->getBody();
        $expectedPayload = new ActionPayload(200, $task);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testActionThrowsInvalidArgumentExceptionWithMissingTitleProperty()
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

        $task = Task::create("title 01","description 01");

        $taskId = (string) $task->getId();

        $data = [
            "title" => null,
            "description" => null
        ];

        $taskRepositoryProphecy = $this->prophesize(TaskRepositoryInterface::class);
        $taskRepositoryProphecy
            ->findTaskOfId($taskId)
            ->willReturn($task)
            ->shouldBeCalledOnce();

        $container->set(TaskRepositoryInterface::class, $taskRepositoryProphecy->reveal());

        $request = $this->createRequest('PUT', "/tasks/{$taskId}");

        $response = $app->handle($request->withParsedBody($data));

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::SERVER_ERROR, 'The title property was not provided.');
        $expectedPayload = new ActionPayload(500, null, $expectedError);

        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }

    public function testActionThrowsInvalidArgumentExceptionWithMissingDescriptionProperty()
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

        $task = Task::create("title 01","description 01");

        $taskId = (string) $task->getId();

        $data = [
            "title" => "title 01",
            "description" => null
        ];

        $taskRepositoryProphecy = $this->prophesize(TaskRepositoryInterface::class);
        $taskRepositoryProphecy
            ->findTaskOfId($taskId)
            ->willReturn($task)
            ->shouldBeCalledOnce();

        $container->set(TaskRepositoryInterface::class, $taskRepositoryProphecy->reveal());

        $request = $this->createRequest('PUT', "/tasks/{$taskId}");

        $response = $app->handle($request->withParsedBody($data));

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::SERVER_ERROR, 'The description property was not provided.');
        $expectedPayload = new ActionPayload(500, null, $expectedError);

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

        $task = Task::create("title 01","description 01");

        $taskId = (string) $task->getId();

        $data = [
            "title" => "title 01",
            "description" => "description 01"
        ];

        $taskRepositoryProphecy = $this->prophesize(TaskRepositoryInterface::class);
        $taskRepositoryProphecy
            ->findTaskOfId(Argument::type("string"))
            ->willThrow(TaskNotFoundException::class)
            ->shouldBeCalledOnce();

        $container->set(TaskRepositoryInterface::class, $taskRepositoryProphecy->reveal());

        $request = $this->createRequest('PUT', "/tasks/1");
        $response = $app->handle($request->withParsedBody($data));

        $payload = (string) $response->getBody();
        $expectedError = new ActionError(ActionError::RESOURCE_NOT_FOUND, 'The task you requested does not exist.');
        $expectedPayload = new ActionPayload(404, null, $expectedError);
        $serializedPayload = json_encode($expectedPayload, JSON_PRETTY_PRINT);

        $this->assertEquals($serializedPayload, $payload);
    }
}

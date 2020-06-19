<?php
declare(strict_types=1);

use App\Application\Actions\User\ListUsersAction;
use App\Application\Actions\User\ViewUserAction;
use App\Application\Actions\Task\ListTasksAction;
use App\Application\Actions\Task\ViewTaskAction;
use App\Application\Actions\Task\CreateTaskAction;
use App\Application\Actions\Task\UpdateTaskAction;
use App\Application\Actions\Task\DeleteTaskAction;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\App;
use Slim\Interfaces\RouteCollectorProxyInterface as Group;

return function (App $app) {
    $app->options('/{routes:.*}', function (Request $request, Response $response) {
        // CORS Pre-Flight OPTIONS Request Handler
        return $response;
    });

    $app->get('/', function (Request $request, Response $response) {
        $response->getBody()->write('{"version":"1.0"}');
        return $response->withHeader('Content-Type', 'application/json');
        return $response;
    });

    $app->get('/health', function (Request $request, Response $response) {
        $response->getBody()->write('{"success":"ok"}');
        return $response->withHeader('Content-Type', 'application/json');
    });

    $app->group('/tasks', function (Group $group) {
        $group->post('', CreateTaskAction::class);
        $group->get('', ListTasksAction::class);
        $group->get('/{id}', ViewTaskAction::class);
        $group->put('/{id}', UpdateTaskAction::class);
        $group->delete('/{id}', DeleteTaskAction::class);
    });
};

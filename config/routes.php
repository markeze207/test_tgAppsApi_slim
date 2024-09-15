<?php
global $app;

use App\Controllers\TasksController;
use App\Controllers\UserController;
use App\Controllers\ValidateController;
use Slim\Routing\RouteCollectorProxy;

$app->post('/auth', function ($request, $response, array $args) {

    global $start;

    $webhook_data = file_get_contents('php://input');
    $data = json_decode($webhook_data, true);

    $validateController = new ValidateController();
    $result = $validateController->startValidate($data);

    $result['time'] = microtime(true) - $start;
    $response->getBody()->write(json_encode($result));
    return $response;
});

// API group
$app->group('/api', function (RouteCollectorProxy $apiGroup) {
    // Users group
    $apiGroup->group('/users', function (RouteCollectorProxy $group) {

        $group->get('/get', function ($request, $response, array $args) {
            global $start;
            $token = $request->getAttribute("token");

            $userController = new UserController($token['sub']);
            $user = $userController->get();
            $user['time'] = microtime(true) - $start;

            $response->getBody()->write(json_encode($user));

            return $response;
        });
        $group->get('/add/{name}', function ($request, $response, array $args) {
            global $start;
            $token = $request->getAttribute("token");

            $userController = new UserController($token['sub']);
            $user = $userController->get();
            $user['time'] = microtime(true) - $start;

            $response->getBody()->write(json_encode($user));

            return $response;
        });

        $group->get('/getTasks', function ($request, $response, array $args) {
            global $start;
            $token = $request->getAttribute("token");

            $userController = new UserController($token['sub']);
            $user = $userController->getTasks();
            $user['time'] = microtime(true) - $start;

            $response->getBody()->write(json_encode($user));

            return $response;
        });
    });

    // Tasks group
    $apiGroup->group('/tasks', function (RouteCollectorProxy $groupTask) {
        $groupTask->get('/getAll', function ($request, $response, array $args) {
            global $start;

            $tasksController = new TasksController();
            $user = $tasksController->getAll();
            $user['time'] = microtime(true) - $start;

            $response->getBody()->write(json_encode($user));

            return $response;
        });
    });
});

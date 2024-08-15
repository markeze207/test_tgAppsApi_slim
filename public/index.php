<?php
$start = microtime(true);

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Cache-Control: public, max-age=50');

use Slim\Factory\AppFactory;

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(dirname(__DIR__));
$dotenv->load();

$app = AppFactory::create();

$app->add(new Tuupola\Middleware\JwtAuthentication([
    "header" => "X-Token",
    "path" => "/api",
    "secret" => $_ENV['SECRET_KEY'],
    "algorithm" => ["HS256"],
    "error" => function ($response, $arguments) {
        $data["status"] = false;

        $response->getBody()->write(
            json_encode(['result' => 'Некорректный токен', 'status' => $data["status"]])
        );

        return $response->withHeader("Content-Type", "application/json");
    }
]));

$customErrorHandler = function () use ($app) {
    $response = $app->getResponseFactory()->createResponse();
    $response->getBody()->write(
        json_encode(['result' => 'Не найден метод','status' => false], JSON_UNESCAPED_UNICODE)
    );

    return $response;
};
$errorMiddleware = $app->addErrorMiddleware(true, true, true);
$errorMiddleware->setDefaultErrorHandler($customErrorHandler);

require __DIR__ . '/../config/routes.php';

// Run app
$app->run();

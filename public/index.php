<?php
$start = microtime(true);
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
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
    "path" => "/api",
    "secret" => $_ENV['SECRET_KEY'],
    "algorithm" => ["HS256"],
    "error" => function ($response, $arguments) {
        $response->getBody()->write(
            json_encode(['result' => 'Некорректный токен', 'status' => false])
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

<?php

use GeekBrains\LevelTwo\HTTP\Likes\CreatePostLike;
use GeekBrains\LevelTwo\Blog\Exceptions\AppException;
use GeekBrains\LevelTwo\HTTP\Actions\Auth\LogIn;
use GeekBrains\LevelTwo\HTTP\Actions\Users\CreateUser;
use GeekBrains\LevelTwo\Http\Actions\Posts\CreatePost;
use GeekBrains\LevelTwo\HTTP\Actions\Users\FindByUsername;
use GeekBrains\LevelTwo\HTTP\ErrorResponse;
use GeekBrains\LevelTwo\HTTP\Request;
use GeekBrains\LevelTwo\Http\Actions\Posts\DeletePost;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';

$logger = $container->get(LoggerInterface::class);

$request = new Request(
    $_GET,
    $_SERVER,
    file_get_contents('php://input'),
);

try {
    $path = $request->path();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}

try {
    $method = $request->method();
} catch (HttpException $e) {
    $logger->warning($e->getMessage());
    (new ErrorResponse)->send();
    return;
}


$routes = [
    'GET' => [
        '/users/show' => FindByUsername::class,
    ],
    'POST' => [
        '/login' => LogIn::class,
        '/users/create' => CreateUser::class,
        '/posts/create' => CreatePost::class,
        '/post-likes/create' => CreatePostLike::class
    ],
    'DELETE' => [
        '/posts' => DeletePost::class,
    ],

];

if (
    !array_key_exists($method, $routes)
    || !array_key_exists($path, $routes[$method])
) {
    $message = "Route not found: $method $path";
    $logger->notice($message);
    (new ErrorResponse($message))->send();
    return;
}

$actionClassName = $routes[$method][$path];

$action = $container->get($actionClassName);

try {
    $response = $action->handle($request);
} catch (AppException $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
    (new ErrorResponse($e->getMessage()))->send();
    return;
}

$response->send();

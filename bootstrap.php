<?php

use GeekBrains\LevelTwo\Blog\Container\DIContainer;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUserRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\SqliteLikesRepository;
use GeekBrains\LevelTwo\HTTP\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\HTTP\Auth\JsonBodyUsernameIdentification;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Dotenv\Dotenv;

require_once __DIR__ . 'vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();

$constainer->bind(
  PDO::class,
  new PDO('sqlite:' . __DIR__ . '/' . $_ENV['SQLITE_DB_PATH'])
);

$logger = (new Logger('blog'));

if ('yes' === $_ENV['LOG_TO_FILES']) {
  $logger->pushHandler(new StreamHandler(
    __DIR__ . '/logs/blog.log'
  ))
    ->pushHandler(new StreamHandler(
      __DIR__ . '/logs/blog.error.log',
      level: Logger::ERROR,
      bubble: false,
    ));
}

if ('yes' === $_ENV['LOG_TO_CONSOLE']) {
  $logger->pushHandler(
    new StreamHandler("php://stdout")
  );
}

$container->bind(
  IdentificationInterface::class,
  JsonBodyUsernameIdentification::class
);

$container->bind(
  LoggerInterface::class,
  $logger

);

$container->bind(
  LikesRepositoryInterface::class,
  SqliteLikesRepository::class
);

$container->bind(
  PostsRepositoryInterface::class,
  SqlitePostsRepository::class
);

$container->bind(
  UsersRepositoryInterface::class,
  SqliteUsersRepository::class
);

return $container;

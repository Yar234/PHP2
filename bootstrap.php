<?php

use GeekBrains\LevelTwo\Blog\Container\DIContainer;
use GeekBrains\LevelTwo\Blog\Repositories\AuthTokensRepository\AuthTokensRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\AuthTokensRepository\SqliteAuthTokensRepository;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUserRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\LikesRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\LikesRepository\SqliteLikesRepository;
use GeekBrains\LevelTwo\HTTP\Auth\AuthenticationInterface;
use GeekBrains\LevelTwo\HTTP\Auth\BearerTokenAuthentication;
use GeekBrains\LevelTwo\HTTP\Auth\IdentificationInterface;
use GeekBrains\LevelTwo\HTTP\Auth\JsonBodyUsernameIdentification;
use GeekBrains\LevelTwo\HTTP\Auth\PasswordAuthentication;
use GeekBrains\LevelTwo\HTTP\Auth\PasswordAuthenticationInterface;
use GeekBrains\LevelTwo\HTTP\Auth\TokenAuthenticationInterface;
use Monolog\Handler\StreamHandler;
use Monolog\Logger;
use Psr\Log\LoggerInterface;
use Dotenv\Dotenv;
use Faker\Generator;
use Faker\Provider\Lorem;
use Faker\Provider\ru_RU\Internet;
use Faker\Provider\ru_RU\Person;
use Faker\Provider\ru_RU\Text;

require_once __DIR__ . '/vendor/autoload.php';

Dotenv::createImmutable(__DIR__)->safeLoad();

$container = new DIContainer();

$container->bind(
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


$faker = new Generator();

$faker->addProvider(new Person($faker));
$faker->addProvider(new Text($faker));
$faker->addProvider(new Internet($faker));
$faker->addProvider(new Lorem($faker));

$container->bind(
  Generator::class,
  $faker
);


$container->bind(
  TokenAuthenticationInterface::class,
  BearerTokenAuthentication::class
);


$container->bind(
  PasswordAuthenticationInterface::class,
  PasswordAuthentication::class
);
$container->bind(
  AuthTokensRepositoryInterface::class,
  SqliteAuthTokensRepository::class
);

$container->bind(
  AuthenticationInterface::class,
  PasswordAuthentication::class
);

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
  SqliteUserRepository::class
);

return $container;

<?php

use GeekBrains\LevelTwo\Blog\Container\DIContainer;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUserRepository;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;

require_once __DIR__ . 'vendor/autoload.php';

$container = new DIContainer();

$constainer->bind(
  PDO::class,
  new PDO('sqlite:' . __DIR__ . '/blog.sqlite')
);

$container->bind(
  PostsRepositoryInterface::class,
  SqlitePostRepository::class
);

$constainer->bind(
  UsersRepositoryInterface::class,
  SqliteUserRepository::class
);

return $constainer;

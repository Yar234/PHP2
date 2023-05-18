<?php

use GeekBrains\LevelTwo\Blog\Command\Arguments;
use GeekBrains\LevelTwo\Blog\Command\CreateUserCommand;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUserRepository;

include __DIR__ . "/vendor/autoload.php";

$connection = new PDO('sqlite:' . __DIR__ . '/blog.sqlite');

$usersRepository = new SqliteUserRepository($connection);

$command = new CreateUserCommand($usersRepository);

try {
    $command->handle(Arguments::fromArgv($argv));
} catch (Exception $e) {
    echo $e->getMessage();
}
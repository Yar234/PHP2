<?php

use GeekBrains\LevelTwo\Blog\Commands\FakeData\PopulateDB;
use GeekBrains\LevelTwo\HTTP\Actions\Posts\DeletePost;
use GeekBrains\LevelTwo\HTTP\Actions\Users\CreateUser;
use GeekBrains\LevelTwo\Blog\Commands\Users\UpdateUser;
use GeekBrains\LevelTwo\Blog\Command\Arguments;
use GeekBrains\LevelTwo\Blog\Command\Users\CreateUserCommand;
use Symfony\Component\Console\Application;
use Psr\Log\LoggerInterface;

$container = require __DIR__ . '/bootstrap.php';

$logger = $container->get(LoggerInterface::class);

$application = new Application();

$commandsClasses = [
    CreateUser::class,
    DeletePost::class,
    Update::class,
    PopulateDB::class
];

foreach ($commandsClasses as $commandClass) {
    $command = $container->get($commandClass);
    $application->add($command);
}

try {
    $application->run();
} catch (Exception $e) {
    $logger->error($e->getMessage(), ['exception' => $e]);
    echo $e->getMessage();
}

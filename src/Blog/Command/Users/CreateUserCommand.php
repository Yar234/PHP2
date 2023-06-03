<?php

namespace GeekBrains\LevelTwo\Blog\Command;

use GeekBrains\LevelTwo\Blog\Exceptions\ArgumentsException;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Person\Name;
use Psr\Log\LoggerInterface;

class CreateUserCommand
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository,
        private LoggerInterface $logger
    ) {
    }

    /**
     * @throws CommandException
     * @throws InvalidArgumentException|ArgumentsException
     */
    public function handle(Arguments $arguments): void
    {
        $this->logger->info("Create user command started");

        $username = $arguments->get('username');

        if ($this->userExists($username)) {
            $this->logger->warning("User already exists: $username");
            throw new CommandException("User already exists: $username");
        }

        $user = User::createFrom(
            $username,
            $arguments->get('password'),
            new Name(
                $arguments->get('first_name'),
                $arguments->get('last_name')
            )
        );

        $this->usersRepository->save($user);

        $this->logger->info("User created: " . $user->uuid());
    }

    private function userExists(string $username): bool
    {
        try {
            $this->usersRepository->getByUsername($username);
        } catch (UserNotFoundException) {
            return false;
        }
        return true;
    }
}

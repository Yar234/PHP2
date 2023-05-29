<?php

namespace GeekBrains\LevelTwo\Blog\Command;

use GeekBrains\LevelTwo\Blog\Exceptions\ArgumentsException;
use GeekBrains\LevelTwo\Blog\Exceptions\CommandException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;

class CreateUserCommand
{
    public function __construct(
        private UsersRepositoryInterface $usersRepository
    ) {
    }

    /**
     * @throws CommandException
     * @throws InvalidArgumentException|ArgumentsException
     */
    public function handle(Arguments $arguments): void
    {
        $username = $arguments->get('username');

        if ($this->userExists($username)) {
            throw new CommandException("User already exists: $username");
        }

        $this->usersRepository->save(new User(
            UUID::random(),
            new Name(
                $arguments->get('first_name'),
                $arguments->get('last_name')
            ),
            $username,
        ));
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

<?php

namespace GeekBrains\LevelTwo\HTTP\Auth;

use GeekBrains\LevelTwo\Blog\Exceptions\AuthException;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\HTTP\Request;
use GeekBrains\LevelTwo\Blog\User;

class JsonBodyUsernameIdentification implements IdentificationInterface
{
  public function __construct(
    private UsersRepositoryInterface $usersRepository
  ) {
  }

  public function user(Request $request): User
  {
    try {
      $username = $request->jsonBodyField('username');
    } catch (HttpException $e) {
      throw new AuthException($e->getMessage());
    }

    try {
      return $this->usersRepository->getByUsername($username);
    } catch (UserNotFoundException $e) {
      throw new AuthException($e->getMessage());
    }
  }
}

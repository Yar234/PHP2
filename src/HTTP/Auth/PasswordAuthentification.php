<?php

namespace GeekBrains\LevelTwo\HTTP\Auth;

use GeekBrains\LevelTwo\Blog\Exceptions\AuthException;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Http\Auth\PasswordAuthenticationInterface;
use GeekBrains\LevelTwo\HTTP\Request;

class PasswordAuthentification implements PasswordAuthenticationInterface
{
  public function __construct(
    private UsersRepositoryInterface $usersRepository
  ) {
  }

  /**
   * @throws AuthException
   */
  public function user(Request $request): User
  {
    try {
      $username = $request->jsonBodyField('username');
    } catch (HttpException $e) {
      throw new AuthException($e->getMessage());
    }

    try {
      $user = $this->usersRepository->getByUsername($username);
    } catch (UserNotFoundException $e) {
      throw new AuthException($e->getMessage());
    }

    try {
      $password = $request->jsonBodyField('password');
    } catch (HttpException $e) {
      throw new AuthException($e->getMessage());
    }

    if (!$user->checkPassword($password)) {
      throw new AuthException('Wrong password');
    }

    return $user;
  }
}

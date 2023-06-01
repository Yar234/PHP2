<?php

namespace GeekBrains\LevelTwo\Http\Auth;

use Dotenv\Exception\InvalidFileException;
use GeekBrains\LevelTwo\Blog\Exceptions\AuthException;
use GeekBrains\LevelTwo\Blog\Exceptions\HttpException;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\UsersRepositoryInterface;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\HTTP\Request;

class JsonBodyUuidIdentification implements IdentificationInterface
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
      $userUuid = new UUID($request->jsonBodyField('user_uuid'));
    } catch (HttpException | InvalidArgumentException $e) {
      throw new AuthException($e->getMessage());
    }

    try {
      return $this->usersRepository->get($userUuid);
    } catch (UserNotFoundException $e) {
      throw new AuthException($e->getMessage());
    }
  }
}

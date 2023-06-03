<?php

namespace GeekBrains\LevelTwo\HTTP\Actions\Auth;

use GeekBrains\LevelTwo\Blog\Exceptions\AuthException;
use GeekBrains\LevelTwo\Blog\Exceptions\AuthTokenNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\AuthTokensRepository\AuthTokensRepositoryInterface;
use GeekBrains\LevelTwo\HTTP\Actions\ActionInterface;
use GeekBrains\LevelTwo\HTTP\Auth\BearerTokenAuthentication;
use GeekBrains\LevelTwo\HTTP\Request;
use GeekBrains\LevelTwo\HTTP\Response;
use GeekBrains\LevelTwo\HTTP\SuccessfulResponse;

class LogOut implements ActionInterface
{
  public function __construct(
    private AuthTokensRepositoryInterface $authTokensRepository,
    private BearerTokenAuthentication $authentication
  ) {
  }


  /**
   * @throws AuthException
   */
  public function handle(Request $request): Response
  {
    $token = $this->authentication->getAuthTokenString($request);

    try {
      $authToken = $this->authTokensRepository->get($token);
    } catch (AuthTokenNotFoundException $e) {
      throw new AuthException($e->getMessage());
    }

    $authToken->setExpiresOn(new \DateTimeImmutable("now"));

    $this->authTokensRepository->save($authToken);

    return new SuccessfulResponse([
      'token' => $authToken->token()
    ]);
  }
}

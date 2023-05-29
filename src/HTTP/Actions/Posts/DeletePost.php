<?php

namespace GeekBrains\LevelTwo\HTTP\Actions\Posts;

use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\HTTP\Actions\ActionInterface;
use GeekBrains\LevelTwo\HTTP\ErrorResponse;
use GeekBrains\LevelTwo\HTTP\SuccessfulResponse;
use GeekBrains\LevelTwo\http\Response;
use GeekBrains\LevelTwo\http\Request;


class DeletePost implements ActionInterface
{
  public function __construct(
    private PostsRepositoryInterface $postsRepository
  ) {
  }


  public function handle(Request $request): Response
  {
    try {
      $postUuid = $request->query('uuid');
      $this->postsRepository->get(new UUID($postUuid));
    } catch (PostNotFoundException $error) {
      return new ErrorResponse($error->getMessage());
    }

    $this->postsRepository->delete(new UUID($postUuid));

    return new SuccessfulResponse([
      'uuid' => $postUuid,
    ]);
  }
}

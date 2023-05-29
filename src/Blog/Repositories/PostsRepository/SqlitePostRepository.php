<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\PostsRepository;

use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\PostsRepositoryInterface;
use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Repositories\UsersRepository\SqliteUserRepository;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;
use PDOStatement;

class SqlitePostRepository implements PostsRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection)
    {
        $this->connection = $connection;
    }

    /**
     * @param Post $post
     * @return void
     */
    public function save(Post $post): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO posts (uuid, author_uuid, title, text)
VALUES (:uuid, :author_uuid, :title, :text)'
        );

        $statement->execute([
            ':uuid' => (string)$post->uuid(),
            ':author_uuid' => (string)$post->getUser()->uuid(),
            ':title' => $post->getTitle(),
            ':text' => $post->getText()
        ]);
    }

    /**
     * @param UUID $uuid
     * @param User $user
     * @return Post
     * @throws InvalidArgumentException
     * @throws PostNotFoundException
     */
    public function get(UUID $uuid, User $user): Post
    {
        $statement = $this->connection->prepare(
            'SELECT * FROM posts WHERE uuid = :uuid'
        );

        $statement->execute([
            ':uuid' => (string)$uuid,
        ]);

        return $this->getPost($statement, $uuid);
    }


    /**
     * @throws PostNotFoundException
     * @throws InvalidArgumentException|UserNotFoundException
     */
    private function getPost(PDOStatement $statement, string $postUuId): Post
    {
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new PostNotFoundException(
                "Cannot find post: $postUuId"
            );
        }

        $userRepository = new SqliteUserRepository($this->connection);
        $user = $userRepository->get(new UUID($result['author_uuid']));

        return new Post(
            new UUID($result['uuid']),
            $user,
            $result['title'],
            $result['text']
        );
    }

    public function delete(UUID $uuid): void
    {
        $statement = $this->connection->prepare(
            'DELETE FROM posts WHERE posts.uuid=:uuid;'
        );

        $statement->execute([
            ':uuid' => $uuid,
        ]);
    }
}

<?php

namespace GeekBrains\LevelTwo\Blog\Repositories\CommentsRepository;

use GeekBrains\LevelTwo\Blog\Exceptions\InvalidArgumentException;
use GeekBrains\LevelTwo\Blog\Exceptions\CommentNotFoundException;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\UUID;
use PDO;

class SqliteCommentRepository implements CommentsRepositoryInterface
{
    private PDO $connection;

    public function __construct(PDO $connection) {
        $this->connection = $connection;
    }

    /**
     * @param Comment $comment
     * @return void
     */
    public function save(Comment $comment): void
    {
        $statement = $this->connection->prepare(
            'INSERT INTO comments (uuid, post_uuid, author_uuid, text)
            VALUES (:uuid, :post_uuid, :author_uuid, :text)'
        );

        $statement->execute([
            ':uuid' => (string)$comment->uuid(),
            ':post_uuid' => (string)$comment->getPost()->uuid(),
            ':author_uuid' => (string)$comment->getUser()->uuid(),
            ':text' => $comment->getText(),
        ]);
    }

    /**
     * @param UUID $uuid
     * @param User $user
     * @param Post $post
     * @return Comment
     * @throws CommentNotFoundException
     * @throws InvalidArgumentException
     */
    public function get(UUID $uuid, User $user, Post $post): Comment
    {

        $statement = $this->connection->prepare(
            'SELECT * FROM comments WHERE uuid = :uuid and author_uuid = :author_uuid and post_uuid = :post_uuid'
        );
        $statement->execute([
            ':uuid' => (string)$uuid,
            ':author_uuid' => (string)$user->uuid(),
            ':post_uuid' => (string)$post->uuid(),
        ]);
        $result = $statement->fetch(PDO::FETCH_ASSOC);

        if ($result === false) {
            throw new CommentNotFoundException(
                "Cannot get comment: $uuid"
            );
        }

        return new Comment(
            new UUID($result['uuid']),
            $user,
            $post,
            $result['text']
        );
    }
}
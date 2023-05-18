<?php

namespace GeekBrains\LevelTwo;

use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;
use GeekBrains\LevelTwo\Blog\Exceptions\PostNotFoundException;
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Repositories\PostsRepository\SqlitePostsRepository;
use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Blog\UUID;
use GeekBrains\LevelTwo\Person\Name;
use function PHPUnit\Framework\once;

class SqlitePostsRepositoryTest extends TestCase
{
    public function testItSavesPostToDatabase(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->expects($this->once())->method('execute')->with([
            ':uuid' => '123e4567-e89b-12d3-a456-426614174000',
            ':authorUuid' => '123e4567-e89b-12d3-a456-426614174001',
            ':headerText' => 'Заголовок',
            ':text' => 'Текст теста',
        ]);
        $connectionStub->method('prepare')->willReturn($statementMock);

        $repositoryPost = new SqlitePostsRepository($connectionStub);
        $repositoryPost->save(new Post(
            new UUID('123e4567-e89b-12d3-a456-426614174000'),
            new User(new UUID('123e4567-e89b-12d3-a456-426614174001'),
            new Name('Zoia', 'Kapustiak'),
            'Admin'),
            'Заголовок',
            'Текст теста')
        );

    }
    public function testItGetPostByUuid(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn([
            'uuid' => '123e4567-e89b-12d3-a456-426614174000',
            'authorUuid' => '123e4567-e89b-12d3-a456-426614174001',
            'headerText' => 'Заголовок',
            'text' => 'Текст теста',
            'login' => 'ivan123',
            'firstName' => 'Ivan',
            'lastName' => 'Nikitin',
        ]);
        $connectionStub->method('prepare')->willReturn($statementMock);
        $repositoryPost = new SqlitePostsRepository($connectionStub);
        $post = $repositoryPost->getByUUID(new UUID('123e4567-e89b-12d3-a456-426614174000'));
        $this->assertSame('123e4567-e89b-12d3-a456-426614174000', (string)$post->uuid());

    }
    public function testItThrowsAnExceptionWhenPostNotFound(): void
    {
        $connectionStub = $this->createStub(PDO::class);
        $statementMock = $this->createMock(PDOStatement::class);
        $statementMock->method('fetch')->willReturn(false);
        $connectionStub->method('prepare')->willReturn($statementMock);
        $repositoryPost = new SqlitePostsRepository($connectionStub);
        $this->expectException(PostNotFoundException::class);
        $this->expectExceptionMessage('Cannot find post: 123e4567-e89b-12d3-a456-426614174000');
        $repositoryPost->getByUUID(new UUID('123e4567-e89b-12d3-a456-426614174000'));
    }

}
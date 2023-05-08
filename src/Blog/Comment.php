<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Blog\Post;

class Comment
{
  private int $id;
  private int $authorId;
  private int $postId;
  private string $title;
  private string $text;


  /**
   * @param int $id
   * @param int $authorId
   * @param int $postId
   * @param string $title
   * @param string $text
   */
  public function __construct(
    int $id,
    int $authorId,
    int $postId,
    string $title,
    string $text
  ) {
    $this->id = $id;
    $this->authorId = $authorId;
    $this->postId = $postId;
    $this->title = $title;
    $this->text = $text;
  }


  public function __toString()
  {
    return $this->authorId . ' пишет: ' . $this->title . '>>>' . $this->text . PHP_EOL;
  }


  public function getId(): int
  {
    return $this->id;
  }

  public function setId($id): void
  {
    $this->id = $id;
  }


  public function getAuthorId(): int
  {
    return $this->authorId;
  }

  public function setAuthorId($authorId): void
  {
    $this->authorId = $authorId;
  }


  public function getText(): string
  {
    return $this->text;
  }

  public function setText($text): void
  {
    $this->text = $text;
  }


  public function getPostId(): string
  {
    return $this->postId;
  }

  public function setPostId($postId): void
  {
    $this->postId = $postId;
  }


  public function getTitle(): string
  {
    return $this->title;
  }

  public function setTitle($title): void
  {
    $this->title = $title;
  }
}

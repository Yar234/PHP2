<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Person\Person;

class Post
{
  private int $id;
  private int $authorId;
  private Person $author;
  private string $title;
  private string $text;

  /**
   * @param int $id
   * @param int $authorId
   * @param Person $author
   * @param string $title
   * @param string $text
   */
  public function __construct(
    int $id,
    int $authorId,
    Person $author,
    string $title,
    string $text
  ) {
    $this->id = $id;
    $this->authorId = $authorId;
    $this->author = $author;
    $this->title = $title;
    $this->text = $text;
  }

  public function __toString()
  {
    return $this->author . ' пишет: ' . $this->title . ' >>> ' . $this->text . PHP_EOL;
  }


  public function getId(): int
  {
    return $this->id;
  }

  public function setId(int $id): void
  {
    $this->id = $id;
  }


  public function getAuthorId(): int
  {
    return $this->authorId;
  }

  public function setAuthorId(int $authorId): void
  {
    $this->authorId = $authorId;
  }


  public function getTitle(): string
  {
    return $this->title;
  }

  public function setTitle(string $title): void
  {
    $this->title = $title;
  }


  public function getText(): string
  {
    return $this->text;
  }

  public function setText(string $text): void
  {
    $this->text = $text;
  }

  public function getAuthor(): Person
  {
    return $this->author;
  }

  public function setAuthor(Person $author): void
  {
    $this->author = $author;
  }
}

<?php

namespace GeekBrains\LevelTwo\Blog;

use GeekBrains\LevelTwo\Person\Name;

class User
{
  private int $id;
  private Name $username;
  private string $login;

  /**
   * @param int $id
   * @param Name $username
   * @param string $login
   */
  public function __construct(
    int $id,
    Name $username,
    string $login
  ) {
    $this->id = $id;
    $this->username = $username;
    $this->login = $login;
  }

  public function __toString(): string
  {
    return "Юзер $this->id с именем $this->username и логином $this->login." . PHP_EOL;
  }


  public function getId(): int
  {
    return $this->id;
  }

  /**
   * @param int $id
   */
  public function setId(int $id): void
  {
    $this->id = $id;
  }


  public function getUsername(): string
  {
    return $this->username;
  }

  /**
   * @param Name $username
   */
  public function setUsername(string $username): void
  {
    $this->username = $username;
  }


  public function getLogin(): string
  {
    return $this->login;
  }


  /**
   * @param string $login
   */
  public function setLogin(string $login): void
  {
    $this->login = $login;
  }
}

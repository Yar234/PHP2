<?php

namespace GeekBrains\LevelTwo\Blog;

class Like
{
  public function __construct(
    private UUID $uuid,
    private UUID $post_id,
    private UUID $user_id
  ) {
  }

  /**
   * @return UUID
   */
  public function uuid(): UUID
  {
    return $this->uuid;
  }

  /**
   * @param UUID $uuid
   */
  public function setUuid(UUID $uuid): void
  {
    $this->uuid = $uuid;
  }


  /**
   * @return UUID
   */
  public function getPost_id(): UUID
  {
    return $this->post_id;
  }

  /**
   * @param UUID $post_id
   */
  public function setPost_id(UUID $post_id): void
  {
    $this->post_id = $post_id;
  }

  /**
   * @return UUID
   */
  public function getUser_id(): UUID
  {
    return $this->user_id;
  }

  /**
   * @param UUID $user_id
   */
  public function setUser_id(UUID $user_id): void
  {
    $this->user_id = $user_id;
  }
}

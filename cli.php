<?php

use GeekBrains\LevelTwo\Blog\User;
use GeekBrains\LevelTwo\Person\{Name, Person};
use GeekBrains\LevelTwo\Blog\Post;
use GeekBrains\LevelTwo\Blog\Comment;
use GeekBrains\LevelTwo\Blog\Repositories\InMemoryUsersRepository;
use GeekBrains\LevelTwo\Blog\Exceptions\UserNotFoundException;

//spl_autoload_register('load');

include __DIR__ . "/vendor/autoload.php";

function load($className)
{
  $file = $className . ".php";
  $file = str_replace(["\\", "_"], "/", $file);

  if (file_exists($file)) {
    include $file;
  }
}
print load("\my\package_name\Class_Name");

var_dump($argv);
//$faker = Faker\Factory::create('ru_RU');

//echo $faker->name() . PHP_EOL;
//echo $faker->realText(rand(100, 200)) . PHP_EOL;

$name = new Name('Peter', 'Sidorov');

$user = new User(1, $name, "Admin");
echo $user;

$name = new Name('Peter', 'Sidorov');
$person = new Person($name, new DateTimeImmutable());

$post = new Post(
  1,
  $user->getId(),
  $person,
  "New post.",
  "Всем привет!"
);

echo $post;

$comment = new Comment(
  1,
  $user->getId(),
  $post->getId(),
  "Excelent",
  "It's awesome"
);

echo $comment;

$name2 = new Name("Ivan", "Taranov");
$user2 = new User(2, $name2, "User");
$userRepository = new InMemoryUsersRepository();
try {
  $userRepository->save($user);
  $userRepository->save($user2);

  echo $userRepository->get(1);
  echo $userRepository->get(2);
  echo $userRepository->get(3);
} catch (UserNotFoundException | Exception $e) {
  echo $e->getMessage();
}

<?php
require_once __DIR__ . "/../config/database.php";
function getAuthors()
{
  global $pdo;

  $query = "select id, name from authors";
  $stnt = $pdo->prepare($query);
  $stnt->execute();
  $authors = $stnt->fetchAll();

  return $authors;
}

function getAuthor()
{
  $author = [
    "id"   => 1,
    "name" => "Andrea Hirata",
    "bio"  => "Penulis asal Belitung, dikenal lewat novel Laskar Pelangi.",
  ];

  return $author;
}

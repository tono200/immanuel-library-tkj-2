<?php
require_once __DIR__ . "/../config/database.php";

function getBooks($search = '', $category_id = '', $currentPage = 1, $perPage = 10)
{
  global $pdo;

  $query = "SELECT b.id, b.title, b.stock, c.name AS category
            FROM books b
            LEFT JOIN categories c ON b.category_id = c.id
            WHERE b.title LIKE :search";
  $params = ['search' => "%$search%"];

  if ($category_id !== '') {
    $query .= " AND b.category_id = :category_id";
    $params['category_id'] = $category_id;
  }

  $query .= " ORDER BY b.id LIMIT :limit OFFSET :offset";
  $stmt = $pdo->prepare($query);
  $stmt->bindValue(':search', $params['search'], PDO::PARAM_STR);
  if ($category_id !== '') {
    $stmt->bindValue(':category_id', $category_id, PDO::PARAM_INT);
  }
  $stmt->bindValue(':limit', max(1, (int) $perPage), PDO::PARAM_INT);
  $stmt->bindValue(':offset', max(0, ((int) $currentPage - 1) * (int) $perPage), PDO::PARAM_INT);
  $stmt->execute();
  $books = $stmt->fetchAll();

  foreach ($books as &$book) {
    $book['authors'] = [];
  }
  unset($book);

  if ($books !== []) {
    $bookIds = array_column($books, 'id');
    $placeholders = implode(',', array_fill(0, count($bookIds), '?'));
    $query = "SELECT ba.book_id, a.name
              FROM book_author ba
              LEFT JOIN authors a ON ba.author_id = a.id
              WHERE ba.book_id IN ($placeholders)";
    $stmt = $pdo->prepare($query);
    $stmt->execute($bookIds);

    $bookIndexes = [];
    foreach ($books as $index => $book) {
      $bookIndexes[$book['id']] = $index;
    }

    foreach ($stmt->fetchAll() as $bookAuthor) {
      if ($bookAuthor['name'] !== null) {
        $books[$bookIndexes[$bookAuthor['book_id']]]['authors'][] = $bookAuthor['name'];
      }
    }
  }

  return $books;
}

function getBook($id)
{
  global $pdo;
  
  $query = "select id, title, isbn, year, stock, category_id, description from books where id = :id";
  $stmt = $pdo->prepare($query);
  $stmt->execute([
    'id' => $id
  ]);

  $book = $stmt->fetch();

  if (!$book) {
    header("Location: ../books/index.php");
    exit;
  }

  $query = "select a.id from book_author ba
            left join authors a on ba.author_id = a.id
            where ba.book_id = :book_id
            ";
  $stmt = $pdo->prepare($query);
  $stmt->execute([
    'book_id' => $book['id']
  ]);

  $authorIds = $stmt->fetchAll();

  $book['author_ids'] = array_column($authorIds, 'id');

  return $book;
}

function isIsbnTaken($isbn, $ignoreBookId)
{
    global $pdo;

    $bookId = intval($ignoreBookId);

    $query = "select count(id) from books where isbn = :isbn AND id != :book_id";

    $stmt = $pdo->prepare($query);
    $stmt->execute([
      'isbn' => $isbn,
      'book_id' => $bookId
    ]);

    $isIsbnTaken = $stmt->fetchColumn() > 0;

    return $isIsbnTaken;
}

function countOfBooks($search, $categoryId)
{
    global $pdo;

    $query = "SELECT COUNT(id) FROM books WHERE title LIKE :title";
    $params = ['title' => "%$search%"];

    if ($categoryId !== '') {
        $query .= " AND category_id = :category_id";
        $params['category_id'] = $categoryId;
    }

    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    return (int) $stmt->fetchColumn();
}
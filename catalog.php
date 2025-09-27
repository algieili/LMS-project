<?php
require_once 'includes/config.php';

$sql = "SELECT id, title, author, isbn, copies, available, description 
        FROM books ORDER BY title ASC";
$result = $mysqli->query($sql);

$books = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $books[] = $row;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Library Catalog</title>
  <style>
    body {
        font-family: Arial, sans-serif;
        background: #f0f2f5;
        margin: 0;
        padding: 20px;
    }

    header h1 {
        text-align: center;
        color: #333;
    }

    /* === Layout === */
    .catalog {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
        gap: 20px;
        margin-top: 20px;
    }

    /* === Book Card === */
    .book {
        border: 1px solid #ddd;
        border-radius: 10px;
        background: #fff;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: transform 0.2s;
    }

    .book:hover {
        transform: scale(1.02);
    }

    .book h3 {
        margin: 0 0 10px;
        font-size: 18px;
        color: #2c3e50;
    }

    .book p {
        margin: 5px 0;
        font-size: 14px;
    }

    /* === Availability States === */
    .available {
        font-weight: bold;
        color: green;
    }

    .unavailable {
        font-weight: bold;
        color: red;
    }

    /* === No Books Message === */
    .no-books {
        text-align: center;
        color: #666;
        font-style: italic;
    }
  </style>
</head>
<body>
  <header>
    <h1>Library Catalog</h1>
  </header>

  <main>
    <div class="catalog">
      <?php if (!empty($books)): ?>
        <?php foreach ($books as $book): ?>
          <article class="book">
            <h3><?= htmlspecialchars($book['title']) ?></h3>
            <p><strong>Author:</strong> <?= htmlspecialchars($book['author']) ?></p>
            <p><strong>ISBN:</strong> <?= htmlspecialchars($book['isbn']) ?></p>
            <p>
              <strong>Copies:</strong> <?= (int)$book['copies'] ?> |
              <?php if ($book['available'] > 0): ?>
                <span class="available"><?= (int)$book['available'] ?> available</span>
              <?php else: ?>
                <span class="unavailable">Not available</span>
              <?php endif; ?>
            </p>
            <?php if (!empty($book['description'])): ?>
              <p class="description"><?= nl2br(htmlspecialchars($book['description'])) ?></p>
            <?php endif; ?>
          </article>
        <?php endforeach; ?>
      <?php else: ?>
        <p class="no-books">No books found in the catalog.</p>
      <?php endif; ?>
    </div>
  </main>
</body>
</html>

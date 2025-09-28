<?php

$host = "localhost";
$dbname = "library_db";
$username = "root";
$password = "";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$title = $author = $year = "";
$title_err = $author_err = $year_err = "";
$success_msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    if (empty(trim($_POST["title"]))) {
        $title_err = "Please enter book title.";
    } else {
        $title = trim($_POST["title"]);
    }
    if (empty(trim($_POST["author"]))) {
        $author_err = "Please enter author name.";
    } else {
        $author = trim($_POST["author"]);
    }
    if (empty(trim($_POST["year"])) || !is_numeric($_POST["year"]) || strlen(trim($_POST["year"])) != 4) {
        $year_err = "Please enter a valid 4-digit year.";
    } else {
        $year = trim($_POST["year"]);
    }

    // Insert data if no errors
    if (empty($title_err) && empty($author_err) && empty($year_err)) {
        $stmt = $conn->prepare("INSERT INTO books (title, author, year) VALUES (?, ?, ?)");
        $stmt->bind_param("ssi", $title, $author, $year);
        if ($stmt->execute()) {
            $success_msg = "Book added successfully!";
            $title = $author = $year = ""; 
        } else {
            echo "Error inserting record: " . $conn->error;
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Add Book</title>
    <style>
        
        body { font-family: Arial; padding: 20px; background: #f0f0f0; }
        .container { background: white; padding: 20px; border-radius: 6px; width: 300px; margin: auto; }
        label { display: block; margin-top: 10px; }
        input[type="text"], input[type="number"] { width: 100%; padding: 8px; margin-top: 4px; }
        .error { color: red; font-size: 0.9em; }
        .success { color: green; font-weight: bold; margin-bottom: 10px; }
        button { margin-top: 10px; padding: 10px; width: 100%; background-color: #007BFF; color: white; border: none; cursor: pointer; }
        button:hover { background: #0056b3; }
    </style>
</head>
<body>
<div class="container">
    <h2>Add New Book</h2>
    <?php if ($success_msg): ?>
        <p class="success"><?php echo $success_msg; ?></p>
    <?php endif; ?>
    <form method="post" action="">
        <label>Title</label>
        <input type="text" name="title" value="<?php echo htmlspecialchars($title); ?>">
        <span class="error"><?php echo $title_err; ?></span>

        <label>Author</label>
        <input type="text" name="author" value="<?php echo htmlspecialchars($author); ?>">
        <span class="error"><?php echo $author_err; ?></span>

        <label>Publication Year</label>
        <input type="number" name="year" value="<?php echo htmlspecialchars($year); ?>">
        <span class="error"><?php echo $year_err; ?></span>

        <button type="submit">Add Book</button>
    </form>
</div>
</body>
</html>

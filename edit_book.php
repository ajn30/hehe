<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

include 'config.php';

// Check if ResourceID is provided
if (!isset($_GET['ResourceID']) || empty($_GET['ResourceID'])) {
    echo "No ResourceID provided!";
    exit;
}

$resourceID = $_GET['ResourceID'];

// Fetch the book details
$stmt = $pdo->prepare("SELECT * FROM LibraryResources WHERE ResourceID = :resourceID");
$stmt->execute(['resourceID' => $resourceID]);
$book = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$book) {
    echo "Book not found!";
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $isbn = $_POST['isbn'];
    $publisher = $_POST['publisher'];
    $year_published = $_POST['year_published'];

    // Update the book details
    $stmt = $pdo->prepare("
        UPDATE LibraryResources 
        SET Title = :title, Author = :author, ISBN = :isbn, Publisher = :publisher, YearPublished = :year_published
        WHERE ResourceID = :resourceID
    ");
    $stmt->execute([
        'title' => $title,
        'author' => $author,
        'isbn' => $isbn,
        'publisher' => $publisher,
        'year_published' => $year_published,
        'resourceID' => $resourceID
    ]);

    header("Location: manage_books.php?message=Book updated successfully");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Book</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
</head>
<body>
<div class="wrapper">
    <div class="content-wrapper">
        <div class="container-fluid">
            <h1>Edit Book</h1>
            <form method="POST">
                <div class="form-group">
                    <label for="title">Title</label>
                    <input type="text" name="title" class="form-control" value="<?php echo htmlspecialchars($book['Title']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="author">Author</label>
                    <input type="text" name="author" class="form-control" value="<?php echo htmlspecialchars($book['Author']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="isbn">ISBN</label>
                    <input type="text" name="isbn" class="form-control" value="<?php echo htmlspecialchars($book['ISBN']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="publisher">Publisher</label>
                    <input type="text" name="publisher" class="form-control" value="<?php echo htmlspecialchars($book['Publisher']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="year_published">Year Published</label>
                    <input type="number" name="year_published" class="form-control" value="<?php echo htmlspecialchars($book['YearPublished']); ?>" required>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="manage_books.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>

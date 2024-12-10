<?php
include 'config.php';

// Check if the form is submitted, otherwise, set default values
$title = $_POST['Title'] ?? '';  // If not submitted, default to empty string
$author = $_POST['Author'] ?? '';
$genre = $_POST['Genre'] ?? '';
$status = $_POST['Status'] ?? '';

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        // Insert into LibraryResources
        $accessionNumber = "B-" . date('Y') . "-" . rand(100, 999);
        $resourceStmt = $pdo->prepare("INSERT INTO LibraryResources (Title, AccessionNumber, Category) VALUES (:title, :accessionNumber, 'Book')");
        $resourceStmt->execute(['title' => $title, 'accessionNumber' => $accessionNumber]);
        $resourceID = $pdo->lastInsertId();

        // Insert into Books
        $bookStmt = $pdo->prepare("INSERT INTO Books (ResourceID, Author, ISBN, Publisher, Edition, PublicationDate) VALUES (:resourceID, :author, :isbn, :publisher, :edition, :publicationDate)");
        $bookStmt->execute([
            'resourceID' => $resourceID,
            'author' => $author,
            'isbn' => $_POST['ISBN'] ?? '',
            'publisher' => $_POST['Publisher'] ?? '',
            'edition' => $_POST['Edition'] ?? '',
            'publicationDate' => $_POST['PublicationDate'] ?? ''
        ]);

        // Success message
        echo "<div class='alert alert-success' role='alert'>Book added successfully!</div>";
    } catch (PDOException $e) {
        // Error message
        echo "<div class='alert alert-danger' role='alert'>Error: " . $e->getMessage() . "</div>";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add New Book</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body>
    <div class="container mt-5">
        <!-- Add Book Form Card -->
        <div class="card shadow-sm">
            <div class="card-header bg-primary text-white">
                <i class="fas fa-book"></i> Add New Book
            </div>
            <div class="card-body">
                <form method="POST">
                    <div class="mb-3">
                        <label for="Title" class="form-label">Book Title</label>
                        <input type="text" class="form-control" name="Title" id="Title" required value="<?php echo htmlspecialchars($title); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="Author" class="form-label">Author</label>
                        <input type="text" class="form-control" name="Author" id="Author" required value="<?php echo htmlspecialchars($author); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="Genre" class="form-label">Genre</label>
                        <input type="text" class="form-control" name="Genre" id="Genre" required value="<?php echo htmlspecialchars($genre); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="Status" class="form-label">Status</label>
                        <select class="form-select" name="Status" id="Status" required>
                            <option value="Available" <?php echo ($status == 'Available') ? 'selected' : ''; ?>>Available</option>
                            <option value="Borrowed" <?php echo ($status == 'Borrowed') ? 'selected' : ''; ?>>Borrowed</option>
                            <option value="Reserved" <?php echo ($status == 'Reserved') ? 'selected' : ''; ?>>Reserved</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="ISBN" class="form-label">ISBN</label>
                        <input type="text" class="form-control" name="ISBN" id="ISBN" required value="<?php echo htmlspecialchars($_POST['ISBN'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="Publisher" class="form-label">Publisher</label>
                        <input type="text" class="form-control" name="Publisher" id="Publisher" value="<?php echo htmlspecialchars($_POST['Publisher'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="Edition" class="form-label">Edition</label>
                        <input type="text" class="form-control" name="Edition" id="Edition" value="<?php echo htmlspecialchars($_POST['Edition'] ?? ''); ?>">
                    </div>
                    <div class="mb-3">
                        <label for="PublicationDate" class="form-label">Publication Date</label>
                        <input type="date" class="form-control" name="PublicationDate" id="PublicationDate" value="<?php echo htmlspecialchars($_POST['PublicationDate'] ?? ''); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Add Book</button>
                </form>
            </div>
        </div>

        <!-- Back Button -->
        <div class="mt-3">
            <a href="books.php" class="btn btn-secondary">Back to Books List</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

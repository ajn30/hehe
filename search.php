<?php
include 'config.php';

// Fetch all books from the database
$stmt = $pdo->prepare("SELECT ResourceID, Title FROM LibraryResources");
$stmt->execute();
$books = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] == 'GET' && isset($_GET['query'])) {
    $query = $_GET['query'];

    // Fetch books that match the selected query
    $stmt = $pdo->prepare("SELECT ResourceID, Title, AccessionNumber, Category FROM LibraryResources WHERE ResourceID = :query");
    $stmt->execute(['query' => $query]);
    $resource = $stmt->fetch();
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Search Library Resources</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Search Library Resources</h1>

        <!-- Search Form with Dropdown -->
        <form method="GET" class="mt-4">
            <div class="mb-3">
                <label for="query" class="form-label">Select Book</label>
                <select name="query" class="form-control" required>
                    <option value="">Select a Book</option>
                    <?php foreach ($books as $book): ?>
                        <option value="<?php echo $book['ResourceID']; ?>">
                            <?php echo $book['Title']; ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Search</button>
        </form>

        <!-- Display Selected Book Details -->
        <?php if (isset($resource)): ?>
            <div class="mt-4">
                <h3>Book Details:</h3>
                <ul class="list-group">
                    <li class="list-group-item">
                        <strong>Resource ID:</strong> <?php echo $resource['ResourceID']; ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Title:</strong> <?php echo $resource['Title']; ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Accession Number:</strong> <?php echo $resource['AccessionNumber']; ?>
                    </li>
                    <li class="list-group-item">
                        <strong>Category:</strong> <?php echo $resource['Category']; ?>
                    </li>
                </ul>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

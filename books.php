<?php
session_start();
if (!isset($_SESSION['membership_type']) || $_SESSION['membership_type'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

include 'config.php';

// Fetch books from the database with error handling for missing values
$stmt = $pdo->query("SELECT BookID, 
                             COALESCE(Title, 'No Title') AS Title, 
                             COALESCE(Author, 'No Author') AS Author, 
                             COALESCE(Genre, 'No Genre') AS Genre, 
                             COALESCE(Status, 'No Status') AS Status 
                      FROM Books");

$books = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Books</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .container {
            margin-top: 50px;
        }
        .table {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn-danger {
            font-size: 1.1rem;
        }
        .btn-primary {
            font-size: 1rem;
        }
    </style>
</head>
<body>

    <div class="container">
        <h2 class="mb-4">Manage Books</h2>
        <a href="admin_dashboard.php" class="btn btn-secondary mb-4">Back to Dashboard</a>
        

        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Title</th>
                    <th>Author</th>
                    <th>Genre</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($books as $book): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($book['BookID']); ?></td>
                        <td><?php echo htmlspecialchars($book['Title']); ?></td>
                        <td><?php echo htmlspecialchars($book['Author']); ?></td>
                        <td><?php echo htmlspecialchars($book['Genre']); ?></td>
                        <td><?php echo htmlspecialchars($book['Status']); ?></td>
                        <td>
                            <a href="edit_book.php?id=<?php echo $book['BookID']; ?>" class="btn btn-warning btn-sm">Edit</a>
                            <a href="delete_book.php?id=<?php echo $book['BookID']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to delete this book?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

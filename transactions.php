<?php
session_start();
include 'config.php';

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];

// Fetch borrowing history from Transactions table
$stmt = $pdo->prepare("
    SELECT LibraryResources.Title, Transactions.BorrowedAt, Transactions.DueDate, Transactions.ReturnedAt 
    FROM Transactions
    JOIN LibraryResources ON Transactions.ResourceID = LibraryResources.ResourceID
    WHERE Transactions.UserID = :user_id AND Transactions.ReturnedAt IS NULL
");
$stmt->execute(['user_id' => $user_id]);
$transactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Borrowing History</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Your Borrowing History</h1>

        <?php if ($transactions): ?>
            <table class="table mt-4">
                <thead>
                    <tr>
                        <th>Book Title</th>
                        <th>Borrowed Date</th>
                        <th>Due Date</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo $transaction['Title']; ?></td>
                            <td><?php echo $transaction['BorrowedAt']; ?></td>
                            <td><?php echo $transaction['DueDate']; ?></td>
                            <td>
                                <?php
                                    if ($transaction['ReturnedAt'] === null) {
                                        echo "<span class='badge bg-warning'>Not Returned</span>";
                                    } else {
                                        echo "<span class='badge bg-success'>Returned</span>";
                                    }
                                ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info">
                You have not borrowed any books yet.
            </div>
        <?php endif; ?>

        <a href="logout.php" class="btn btn-danger mt-3">Logout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

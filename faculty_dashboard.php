<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Faculty') {
    header("Location: login.php");
    exit;
}

include 'config.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Faculty Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script> <!-- For FontAwesome icons -->
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h1>Welcome, <?php echo $_SESSION['name']; ?> (Faculty)</h1>
            </div>
            <div class="card-body">
                <div class="mt-4">
                    <h3>Quick Links</h3>
                    <ul class="list-group">
                        <li class="list-group-item"><a href="search.php">Search Library Resources</a></li>
                        <li class="list-group-item"><a href="transactions.php">View Borrowing History</a></li>
                    </ul>
                </div>
                
                <div class="mt-4">
                    <h3>Currently Borrowed Books</h3>
                    <table class="table table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Accession Number</th>
                                <th>Title</th>
                                <th>Borrowed Date</th>
                                <th>Due Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $stmt = $pdo->prepare("
                                SELECT LibraryResources.AccessionNumber, LibraryResources.Title, Transactions.BorrowedAt, Transactions.DueDate
                                FROM Transactions
                                JOIN LibraryResources ON Transactions.ResourceID = LibraryResources.ResourceID
                                WHERE Transactions.UserID = :user_id AND Transactions.ReturnedAt IS NULL
                            ");
                            $stmt->execute(['user_id' => $_SESSION['user_id']]);
                            $transactions = $stmt->fetchAll();
                            
                            foreach ($transactions as $transaction):
                            ?>
                                <tr>
                                    <td><?php echo $transaction['AccessionNumber']; ?></td>
                                    <td><?php echo $transaction['Title']; ?></td>
                                    <td><?php echo $transaction['BorrowedAt']; ?></td>
                                    <td><?php echo $transaction['DueDate']; ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <a href="logout.php" class="btn btn-danger mt-4"><i class="fas fa-sign-out-alt"></i> Logout</a>
            </div>
        </div>
    </div>
    
    <!-- Bootstrap JS and dependencies (optional for interactive features like tooltips or modals) -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.min.js"></script>
</body>
</html>

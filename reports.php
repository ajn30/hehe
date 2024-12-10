<?php
include 'config.php';

// Initialize variables
$transactions = [];
$overdue = [];
$inventory = [];

// Borrowing history for a user
if (isset($_GET['user_id'])) {
    $userID = $_GET['user_id'];
    $stmt = $pdo->prepare("SELECT Transactions.TransactionID, LibraryResources.Title, Transactions.BorrowedAt, Transactions.DueDate, Transactions.ReturnedAt, Transactions.Fine 
        FROM Transactions 
        JOIN LibraryResources ON Transactions.ResourceID = LibraryResources.ResourceID 
        WHERE Transactions.UserID = :userID");
    $stmt->execute(['userID' => $userID]);
    $transactions = $stmt->fetchAll();
}

// Overdue items
$stmt = $pdo->prepare("SELECT Users.Name, LibraryResources.Title, Transactions.DueDate, DATEDIFF(NOW(), Transactions.DueDate) AS DaysLate 
    FROM Transactions 
    JOIN LibraryResources ON Transactions.ResourceID = LibraryResources.ResourceID 
    JOIN Users ON Transactions.UserID = Users.UserID 
    WHERE Transactions.ReturnedAt IS NULL AND Transactions.DueDate < NOW()");
$stmt->execute();
$overdue = $stmt->fetchAll();

// Inventory summary
$stmt = $pdo->query("SELECT Category, COUNT(*) AS Total FROM LibraryResources GROUP BY Category");
$inventory = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Report</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Sidebar styles */
        .sidebar {
            position: fixed;
            top: 0;
            left: 0;
            bottom: 0;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar a {
            color: white;
            text-decoration: none;
            padding: 10px;
            display: block;
        }
        .sidebar a:hover {
            background-color: #575d63;
        }
        .content {
            margin-left: 270px;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<div class="sidebar">
    <h3 class="text-center text-white">Admin Menu</h3>
    <a href="admin_dashboard.php">Dashboard</a>
    <a href="manage_users.php">Manage Users</a>
    <a href="manage_books.php">Manage Books</a>
    <a href="reports.php">Library Reports</a>
    <a href="addBook.php">Add Book</a>
    <a href="logout.php">Logout</a>
</div>

<!-- Main Content -->
<div class="content">
    <div class="container mt-5">
        <h1 class="text-center">Library Reports</h1>
        
        <!-- Form for Borrowing History -->
        <form method="GET" class="mb-4">
            <div class="row">
                <div class="col-md-6">
                    <label for="user_id" class="form-label">User ID</label>
                    <input type="text" name="user_id" class="form-control" placeholder="Enter User ID">
                </div>
                <div class="col-md-6">
                    <button type="submit" class="btn btn-primary mt-4">Generate Borrowing History</button>
                </div>
            </div>
        </form>
        
        <!-- Borrowing History Section -->
        <?php if (!empty($transactions)): ?>
            <h2>Borrowing History for User ID <?php echo htmlspecialchars($userID); ?>:</h2>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Transaction ID</th>
                        <th>Title</th>
                        <th>Borrowed At</th>
                        <th>Due Date</th>
                        <th>Returned At</th>
                        <th>Fine</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $transaction): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($transaction['TransactionID']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['Title']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['BorrowedAt']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['DueDate']); ?></td>
                            <td><?php echo htmlspecialchars($transaction['ReturnedAt']); ?></td>
                            <td>$<?php echo htmlspecialchars($transaction['Fine']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php elseif (isset($userID)): ?>
            <p>No borrowing history found for User ID <?php echo htmlspecialchars($userID); ?>.</p>
        <?php endif; ?>

        <!-- Overdue Items Section -->
        <h2>Overdue Items:</h2>
        <?php if (!empty($overdue)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>User</th>
                        <th>Title</th>
                        <th>Due Date</th>
                        <th>Days Late</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($overdue as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['Name']); ?></td>
                            <td><?php echo htmlspecialchars($item['Title']); ?></td>
                            <td><?php echo htmlspecialchars($item['DueDate']); ?></td>
                            <td><?php echo htmlspecialchars($item['DaysLate']); ?> days</td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No overdue items.</p>
        <?php endif; ?>

        <!-- Inventory Summary Section -->
        <h2>Inventory Summary:</h2>
        <?php if (!empty($inventory)): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Category</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inventory as $item): ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['Category']); ?></td>
                            <td><?php echo htmlspecialchars($item['Total']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No inventory data available.</p>
        <?php endif; ?>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

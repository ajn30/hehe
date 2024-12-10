<?php
session_start();

// Ensure the user is an admin
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

include 'config.php'; // Database connection

// Approve borrow request
if (isset($_POST['approve_borrow_request'])) {
    $transactionId = $_POST['transaction_id'];

    // Update the status of the transaction to "borrowed"
    $stmt = $pdo->prepare("UPDATE Transactions SET Status = 'borrowed' WHERE TransactionID = :transaction_id AND Status = 'pending'");
    $stmt->execute(['transaction_id' => $transactionId]);

    header("Location: admin_dashboard.php"); // Refresh the page after approval
    exit;
}

// Fetch pending borrow requests
$stmt = $pdo->prepare("SELECT Transactions.TransactionID, LibraryResources.AccessionNumber, LibraryResources.Title, Users.Name AS StudentName, Transactions.BorrowedAt, Transactions.DueDate FROM Transactions 
JOIN LibraryResources ON Transactions.ResourceID = LibraryResources.ResourceID
JOIN Users ON Transactions.UserID = Users.UserID
WHERE Transactions.Status = 'pending'");
$stmt->execute();
$pendingTransactions = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card-header {
            background-color: #007bff;
            color: white;
        }
        .container {
            margin-top: 50px;
        }
        /* Sidebar styling */
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 250px;
            background-color: #343a40;
            color: white;
            padding-top: 20px;
        }
        .sidebar .nav-link {
            color: white;
        }
        .sidebar .nav-link:hover {
            background-color: #007bff;
        }
        .content {
            margin-left: 260px;
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <div class="sidebar">
        <h3 class="text-center text-white">Admin Panel</h3>
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link" href="admin_dashboard.php">Dashboard</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_users.php">Manage Users</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="manage_books.php">Manage Books</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="reports.php">View Reports</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="addBook.php">Add Book</a>
            </li>
            <li class="nav-item">
                <a class="nav-link text-danger" href="?logout">Logout</a>
            </li>
        </ul>
    </div>

    <!-- Main Content -->
    <div class="content">
        <div class="container mt-5">
            <div class="card shadow-sm">
                <div class="card-header">
                    <h1>Welcome, Admin</h1>
                </div>
                <div class="card-body">
                    <h3>Pending Borrow Requests</h3>
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Accession Number</th>
                                <th>Title</th>
                                <th>Student Name</th>
                                <th>Borrowed Date</th>
                                <th>Due Date</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            if ($pendingTransactions) {
                                foreach ($pendingTransactions as $transaction):
                            ?>
                                <tr>
                                    <td><?php echo $transaction['AccessionNumber']; ?></td>
                                    <td><?php echo $transaction['Title']; ?></td>
                                    <td><?php echo $transaction['StudentName']; ?></td>
                                    <td><?php echo $transaction['BorrowedAt']; ?></td>
                                    <td><?php echo $transaction['DueDate']; ?></td>
                                    <td>
                                        <!-- Approve Form -->
                                        <form method="POST">
                                            <input type="hidden" name="transaction_id" value="<?php echo $transaction['TransactionID']; ?>">
                                            <button type="submit" name="approve_borrow_request" class="btn btn-success">Approve</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                            <?php } else { ?>
                                <tr><td colspan="6" class="text-center">No pending requests.</td></tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

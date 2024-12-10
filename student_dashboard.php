<?php
session_start();

// Logout logic
if (isset($_GET['logout'])) {
    session_unset(); // Remove all session variables
    session_destroy(); // Destroy the session
    header("Location: login.php"); // Redirect to login page
    exit;
}

// Role validation for student
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Student') {
    header("Location: login.php");
    exit;
}

include 'config.php'; // Database connection

// Fetch current borrowings for the logged-in student
$stmt = $pdo->prepare("SELECT Transactions.TransactionID, LibraryResources.AccessionNumber, LibraryResources.Title, Transactions.BorrowedAt, Transactions.DueDate, Transactions.Status 
                       FROM Transactions
                       JOIN LibraryResources ON Transactions.ResourceID = LibraryResources.ResourceID
                       WHERE Transactions.UserID = :user_id AND Transactions.ReturnedAt IS NULL");
$stmt->execute(['user_id' => $_SESSION['user_id']]);
$transactions = $stmt->fetchAll();

// Handle Borrow Book request
if (isset($_POST['borrow_book'])) {
    $resourceId = $_POST['resource_id'];
    $borrowDate = date('Y-m-d H:i:s');
    $dueDate = date('Y-m-d', strtotime('+7 days')); // Assume 7 days for borrowing
    $status = 'pending'; // Initially set to pending

    // Insert borrow request into Transactions table
    $stmt = $pdo->prepare("INSERT INTO Transactions (UserID, ResourceID, BorrowedAt, DueDate, Status) VALUES (:user_id, :resource_id, :borrowed_at, :due_date, :status)");
    $stmt->execute(['user_id' => $_SESSION['user_id'], 'resource_id' => $resourceId, 'borrowed_at' => $borrowDate, 'due_date' => $dueDate, 'status' => $status]);

    header("Location: student_dashboard.php"); // Redirect after borrowing request
    exit;
}

// Handle Return Book request
if (isset($_POST['return_book'])) {
    $transactionId = $_POST['transaction_id'];

    // Update the status to returned in the Transactions table
    $stmt = $pdo->prepare("UPDATE Transactions SET ReturnedAt = NOW(), Status = 'returned' WHERE TransactionID = :transaction_id");
    $stmt->execute(['transaction_id' => $transactionId]);

    header("Location: student_dashboard.php"); // Redirect after returning
    exit;
}

// Handle Approve Borrowing (this is typically an admin function, so you may want to add extra logic for admins)
if (isset($_POST['approve_borrow'])) {
    $transactionId = $_POST['transaction_id'];

    // Update the transaction status to 'approved' when the borrow request is approved
    $stmt = $pdo->prepare("UPDATE Transactions SET Status = 'approved' WHERE TransactionID = :transaction_id");
    $stmt->execute(['transaction_id' => $transactionId]);

    header("Location: student_dashboard.php"); // Redirect after approval
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome, <?php echo $_SESSION['name']; ?> (Student)</h1>

        <!-- Quick Links Section -->
        <div class="mb-4">
            <h3>Quick Links</h3>
            <ul>
                <li><a href="search.php">Search Library Resources</a></li>
                <li><a href="transactions.php">View Borrowing History</a></li>
                <li><a href="#view_all_books" data-bs-toggle="collapse">View All Books</a></li>
            </ul>
        </div>

        <!-- Borrowed Books Table -->
        <h3>Currently Borrowed Books</h3>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Accession Number</th>
                    <th>Title</th>
                    <th>Borrowed Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Borrow</th>
                    <th>Return</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $transaction): ?>
                    <tr>
                        <td><?php echo $transaction['AccessionNumber']; ?></td>
                        <td><?php echo $transaction['Title']; ?></td>
                        <td><?php echo $transaction['BorrowedAt']; ?></td>
                        <td><?php echo $transaction['DueDate']; ?></td>
                        <td>
                            <?php if ($transaction['Status'] == 'pending'): ?>
                                <span class="badge bg-warning">Pending</span>
                            <?php elseif ($transaction['Status'] == 'approved'): ?>
                                <span class="badge bg-success">Approved</span>
                            <?php else: ?>
                                <span class="badge bg-success">Returned</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?php if ($transaction['Status'] == 'approved'): ?>
                                <form method="POST">
                                    <input type="hidden" name="transaction_id" value="<?php echo $transaction['TransactionID']; ?>">
                                    <button type="submit" name="return_book" class="btn btn-danger">Return Book</button>
                                </form>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Borrow a Book Section -->
        <div class="mt-4">
            <h3>Borrow a Book</h3>
            <form method="POST">
                <div class="mb-3">
                    <label for="resource_id" class="form-label">Select a Book</label>
                    <select name="resource_id" class="form-select" required>
                        <option value="">Select a book</option>
                        <?php
                        // Get all available books
                        $stmt = $pdo->prepare("SELECT ResourceID, Title FROM LibraryResources WHERE ResourceID NOT IN (SELECT ResourceID FROM Transactions WHERE UserID = :user_id AND ReturnedAt IS NULL)");
                        $stmt->execute(['user_id' => $_SESSION['user_id']]);
                        $books = $stmt->fetchAll();

                        foreach ($books as $book):
                        ?>
                            <option value="<?php echo $book['ResourceID']; ?>"><?php echo $book['Title']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <button type="submit" name="borrow_book" class="btn btn-primary">Borrow Book</button>
            </form>
        </div>

        <!-- View All Books Section -->
        <div class="row mt-4 collapse" id="view_all_books">
            <div class="col-md-12">
                <h3>All Available Books</h3>
                <table class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>Accession Number</th>
                            <th>Title</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        // Fetch all available books
                        $stmt = $pdo->prepare("SELECT AccessionNumber, Title FROM LibraryResources");
                        $stmt->execute();
                        $allBooks = $stmt->fetchAll();

                        foreach ($allBooks as $book):
                        ?>
                            <tr>
                                <td><?php echo $book['AccessionNumber']; ?></td>
                                <td><?php echo $book['Title']; ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Logout Button -->
        <a href="?logout" class="btn btn-danger btn-sm">Logout</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

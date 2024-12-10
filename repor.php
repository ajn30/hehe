<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

include 'config.php';

// Debugging session info
echo '<pre>';
print_r($_SESSION);  // Check session variables
echo '</pre>';

$reportsData = [];
try {
    $stmt = $pdo->prepare("SELECT b.BookID, b.BookTitle, u.Name AS UserName, br.BorrowedDate, br.DueDate, br.Status AS BorrowStatus
                            FROM borrowedbooks br
                            JOIN Books b ON br.BookID = b.BookID
                            JOIN Users u ON br.UserID = u.UserID
                            WHERE br.Status = 'Borrowed' OR br.Status = 'Overdue'
                            ORDER BY br.DueDate DESC");
    $stmt->execute();
    $reportsData = $stmt->fetchAll();
} catch (PDOException $e) {
    $error_message = "Error fetching reports: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Reports</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Library Borrowed Books Report</h1>
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>
        
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Book ID</th>
                    <th>Book Title</th>
                    <th>User Name</th>
                    <th>Borrowed Date</th>
                    <th>Due Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($reportsData)): ?>
                    <?php foreach ($reportsData as $report): ?>
                        <tr>
                            <td><?php echo $report['BookID']; ?></td>
                            <td><?php echo $report['BookTitle']; ?></td>
                            <td><?php echo $report['UserName']; ?></td>
                            <td><?php echo $report['BorrowedDate']; ?></td>
                            <td><?php echo $report['DueDate']; ?></td>
                            <td><?php echo $report['BorrowStatus']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="6" class="text-center">No borrowed books found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <a href="admin_dashboard.php" class="btn btn-secondary">Back to Dashboard</a>
    </div>
</body>
</html>

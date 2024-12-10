<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Staff') {
    header("Location: login.php");
    exit;
}

include 'config.php';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Staff Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Welcome, <?php echo $_SESSION['name']; ?> (Staff)</h1>
        <ul>
            <li><a href="borrow_modal.php">Borrow Books</a></li>
            <li><a href="return_modal.php">Return Books</a></li>
            <li><a href="search.php">Search Resources</a></li>
        </ul>
        <a href="logout.php" class="btn btn-danger">Logout</a>
    </div>
</body>
</html>

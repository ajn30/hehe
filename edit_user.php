<?php
session_start();
if (!isset($_SESSION['role']) || $_SESSION['role'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

include 'config.php'; // Database connection settings

// Check if a UserID is provided in the URL
if (!isset($_GET['UserID']) || empty($_GET['UserID'])) {
    echo "No UserID provided!";
    exit;
}

$userID = $_GET['UserID'];

// Fetch user details from the database
$stmt = $pdo->prepare("SELECT * FROM users WHERE UserID = :userID");
$stmt->execute(['userID' => $userID]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

// Check if the user exists
if (!$user) {
    echo "User not found!";
    exit;
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];

    // Validate inputs
    if (empty($username) || empty($email) || empty($role)) {
        $error = "All fields are required!";
    } else {
        // Update user in the database
        $updateStmt = $pdo->prepare("
            UPDATE users 
            SET Username = :username, Email = :email, Role = :role 
            WHERE UserID = :userID
        ");
        $updated = $updateStmt->execute([
            'username' => $username,
            'email' => $email,
            'role' => $role,
            'userID' => $userID
        ]);

        if ($updated) {
            header("Location: manage_users.php?message=User updated successfully");
            exit;
        } else {
            $error = "Failed to update user. Please try again.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/css/adminlte.min.css">
    <script src="https://kit.fontawesome.com/a076d05399.js"></script>
</head>
<body class="hold-transition sidebar-mini">
<div class="wrapper">
    <div class="content-wrapper">
        <div class="container-fluid">
            <h1>Edit User</h1>

            <!-- Display error if exists -->
            <?php if (isset($error)): ?>
                <div class="alert alert-danger"><?php echo $error; ?></div>
            <?php endif; ?>

            <!-- Edit User Form -->
            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Username:</label>
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo htmlspecialchars($user['Username']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="email">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" value="<?php echo htmlspecialchars($user['Email']); ?>" required>
                </div>
                <div class="form-group">
                    <label for="role">Role:</label>
                    <select class="form-control" id="role" name="role" required>
                        <option value="Admin" <?php echo ($user['Role'] === 'Admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="Student" <?php echo ($user['Role'] === 'Student') ? 'selected' : ''; ?>>Student</option>
                        <option value="Faculty" <?php echo ($user['Role'] === 'Faculty') ? 'selected' : ''; ?>>Faculty</option>
                        <option value="Staff" <?php echo ($user['Role'] === 'Staff') ? 'selected' : ''; ?>>Staff</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Save Changes</button>
                <a href="manage_users.php" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
</div>
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.2/dist/js/adminlte.min.js"></script>
</body>
</html>

<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Fetch user details by email and check if the account is active
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE Email = :email AND Status = 'Active'");
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user) {
        // If user exists, verify the password
        if (password_verify($password, $user['Password'])) {
            // Store user information in session
            $_SESSION['user_id'] = $user['UserID'];
            $_SESSION['name'] = $user['Name'];
            $_SESSION['role'] = $user['MembershipType'];  // Store role in session variable

            // Redirect based on role
            if ($user['MembershipType'] == 'Student') {
                header("Location: student_dashboard.php");  // Redirect to student dashboard
                exit;

            } elseif ($user['MembershipType'] == 'Admin') {
                header("Location: admin_dashboard.php");  // Redirect to admin dashboard
                exit;

            } elseif ($user['MembershipType'] == 'Staff') {
                header("Location: staff_dashboard.php");  // Redirect to staff dashboard
                exit;
                
            } elseif ($user['MembershipType'] == 'Faculty') {
                header("Location: faculty_dashboard.php");  // Redirect to faculty dashboard
                exit;
            }
        } else {
            // Password verification failed
            $error_message = "Invalid email or password.";
        }
    } else {
        // User does not exist
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Library Login</h2>
        
        <?php if (isset($error_message)): ?>
            <div class="alert alert-danger">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" name="password" required>
            </div>
            <button type="submit" class="btn btn-primary">Login</button>
        </form>
        
        <p class="mt-3">Don't have an account? <a href="register.php">Register here</a>.</p>
    </div>
</body>
</html>

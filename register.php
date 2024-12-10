<?php
session_start();
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);  // Hash the password
    $membership_type = $_POST['membership_type'];  // Get membership type from form

    // Set maximum books based on membership type
    $max_books = 0;
    if ($membership_type === 'Student') {
        $max_books = 3;
    } elseif ($membership_type === 'Faculty') {
        $max_books = 5;
    } elseif ($membership_type === 'Staff') {
        $max_books = 7;
    }

    try {
        // Insert the user into the Users table
        $stmt = $pdo->prepare("
            INSERT INTO Users (Name, Email, Password, MembershipType, MaxBooks)
            VALUES (:name, :email, :password, :membership_type, :max_books)
        ");
        $stmt->execute([
            'name' => $name,
            'email' => $email,
            'password' => $password,
            'membership_type' => $membership_type,  // Insert membership type
            'max_books' => $max_books,
        ]);

        // Redirect after successful registration
        header("Location: login.php?registered=true");
        exit;
    } catch (PDOException $e) {
        $error = "Error: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Registration</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h1>Library User Registration</h1>

        <?php if (!empty($error)): ?>
            <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <form method="POST" action="register.php">
            <div class="mb-3">
                <label for="name" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="name" name="name" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <div class="mb-3">
                <label for="membership_type" class="form-label">Membership Type</label>
                <select class="form-select" id="membership_type" name="membership_type" required>
                    <option value="Admin">Admin</option>
                    <option value="Student">Student</option>
                    <option value="Faculty">Faculty</option>
                    <option value="Staff">Staff</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Register</button>
        </form>

        <p class="mt-3">Already have an account? <a href="login.php">Login here</a>.</p>
    </div>
</body>
</html>

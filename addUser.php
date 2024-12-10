<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $membershipType = $_POST['membership_type'];
    $maxBooks = $membershipType == 'Student' ? 3 : ($membershipType == 'Faculty' ? 5 : 2);

    $sql = "INSERT INTO Users (Name, Email, MembershipType, MaxBooks) VALUES (:name, :email, :membershipType, :maxBooks)";
    $stmt = $pdo->prepare($sql);

    try {
        $stmt->execute(['name' => $name, 'email' => $email, 'membershipType' => $membershipType, 'maxBooks' => $maxBooks]);
        echo "User added successfully.";
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
?>
<!-- HTML form for adding a user -->
<form method="POST">
    <label>Name:</label> <input type="text" name="name" required><br>
    <label>Email:</label> <input type="email" name="email" required><br>
    <label>Membership Type:</label>
    <select name="membership_type" required>
        <option value="Student">Student</option>
        <option value="Faculty">Faculty</option>
        <option value="Staff">Staff</option>
    </select><br>
    <button type="submit">Add User</button>
</form>

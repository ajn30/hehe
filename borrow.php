<?php
include 'config.php';

// Function to sanitize user input
function sanitize_input($data) {
    return htmlspecialchars(trim($data));
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Sanitize input data
    $userID = sanitize_input($_POST['user_id']);
    $resourceID = sanitize_input($_POST['resource_id']);
    $dueDate = sanitize_input($_POST['due_date']);

    // Validate the inputs
    if (!is_numeric($userID) || !is_numeric($resourceID)) {
        echo "Invalid user or resource ID.";
        exit;
    }

    // Check borrowing limit
    $stmt = $pdo->prepare("SELECT MaxBooks, 
        (SELECT COUNT(*) FROM Transactions WHERE UserID = :userID AND ReturnedAt IS NULL) AS CurrentBorrowed 
        FROM Users WHERE UserID = :userID");
    $stmt->execute(['userID' => $userID]);
    $result = $stmt->fetch();

    if (!$result) {
        echo "User not found.";
    } elseif ($result['CurrentBorrowed'] >= $result['MaxBooks']) {
        echo "User has reached the borrowing limit.";
    } else {
        try {
            // Insert transaction if the user can borrow more resources
            $transactionStmt = $pdo->prepare("INSERT INTO Transactions (UserID, ResourceID, DueDate) VALUES (:userID, :resourceID, :dueDate)");
            $transactionStmt->execute(['userID' => $userID, 'resourceID' => $resourceID, 'dueDate' => $dueDate]);

            echo "Borrowing transaction recorded successfully.";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
    }
}
?>

<!-- HTML form for borrowing -->
<form method="POST">
    <label for="user_id">User ID:</label>
    <input type="text" name="user_id" id="user_id" required><br>
    
    <label for="resource_id">Resource ID:</label>
    <input type="text" name="resource_id" id="resource_id" required><br>
    
    <label for="due_date">Due Date:</label>
    <input type="date" name="due_date" id="due_date" required><br>
    
    <button type="submit">Borrow</button>
</form>

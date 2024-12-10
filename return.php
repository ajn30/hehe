<?php
include 'config.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $transactionID = $_POST['transaction_id'];

    // Fetch transaction details
    $stmt = $pdo->prepare("SELECT DueDate, DATEDIFF(NOW(), DueDate) AS DaysLate FROM Transactions WHERE TransactionID = :transactionID AND ReturnedAt IS NULL");
    $stmt->execute(['transactionID' => $transactionID]);
    $transaction = $stmt->fetch();

    if (!$transaction) {
        echo "Invalid or already returned transaction.";
    } else {
        $fine = 0;
        if ($transaction['DaysLate'] > 0) {
            $fine = $transaction['DaysLate'] * 2; // Assuming $2 fine per day
        }

        // Update transaction
        $updateStmt = $pdo->prepare("UPDATE Transactions SET ReturnedAt = NOW(), Fine = :fine WHERE TransactionID = :transactionID");
        $updateStmt->execute(['fine' => $fine, 'transactionID' => $transactionID]);

        echo "Book returned successfully. Fine: $" . $fine;
    }
}
?>
<!-- HTML form for returning -->
<form method="POST">
    <label>Transaction ID:</label> <input type="text" name="transaction_id" required><br>
    <button type="submit">Return</button>
</form>

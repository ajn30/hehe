<?php
session_start();
if (!isset($_SESSION['membership_type']) || $_SESSION['membership_type'] !== 'Admin') {
    header("Location: login.php");
    exit;
}

include 'config.php';

// Get the book ID from the URL parameter
if (!isset($_GET['id'])) {
    echo "Book ID is required!";
    exit;
}

$book_id = $_GET['id'];

// Delete the book from the database
$stmt = $pdo->prepare("DELETE FROM Books WHERE BookID = :id");
$stmt->execute(['id' => $book_id]);

// Redirect to the books management page after successful deletion
header("Location: books.php");
exit;
?>

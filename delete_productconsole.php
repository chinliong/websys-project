<?php
session_start();
require 'db_con.php'; // Ensure this path is correct

// Check if user is logged in and has the right role
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || $_SESSION['role'] != 'a') {
    header("location: login.php");
    exit;
}

// Check if product_id is present
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];

    // Prepare the DELETE statement to remove the product from the database
    $stmt = $conn->prepare("DELETE FROM product_table WHERE product_id = ?");
    $stmt->bind_param("i", $product_id);

    if ($stmt->execute()) {
        // Redirect back to console.php with a success message
        header('Location: console.php?message=Product deleted successfully');
    } else {
        echo "Error deleting record: " . $conn->error;
    }

    $stmt->close();
} else {
    header('Location: console.php?error=Invalid request');
}

$conn->close();
?>

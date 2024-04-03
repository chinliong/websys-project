<?php
session_start();
include 'db_con.php'; // Make sure this includes your database connection

if (isset($_POST['cart_id']) && isset($_SESSION['userid'])) {
    $cartId = $_POST['cart_id'];
    $userId = $_SESSION['userid']; // Additional check to ensure users can only delete their items
    
    $stmt = $conn->prepare("DELETE FROM cart_table WHERE cart_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $cartId, $userId);
    
    if ($stmt->execute()) {
        // Redirect back to the shopping cart with a success message
        header("Location: shopping_cart.php?status=success");
    } else {
        // Redirect back with an error message
        header("Location: shopping_cart.php?status=error");
    }
    
    $stmt->close();
    $conn->close();
} else {
    // Redirect or show an error if the cart_id or user session is missing
    header("Location: shopping_cart.php?status=invalid");
}
?>

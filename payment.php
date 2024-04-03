<?php
session_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

if (!isset($_SESSION['userid'])) {
    die("You must be logged in to access this page.");
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $userId = $_SESSION['userid']; // Buyer's user ID

    // Load database configuration
    $config = parse_ini_file('/var/www/private/db-config.ini');
    if (!$config) {
        die("Failed to read database config file.");
    }

    // Create database connection
    $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $conn->begin_transaction();

    try {
        $totalAmountToDeduct = 0;
        
        // Get the current user balance first
        $stmt = $conn->prepare("SELECT funds FROM user_table WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $balance_column = $stmt->get_result();
        $current_user_balance = $balance_column->fetch_assoc()['funds'];
        
        // Retrieve items from the cart table for the current user
        $stmt = $conn->prepare("SELECT pt.product_id, pt.price, pt.user_id AS seller_id FROM cart_table ct JOIN product_table pt ON ct.product_id = pt.product_id WHERE ct.user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                $totalAmountToDeduct += $product['price'];
            }
        } else {
            throw new Exception("Cart is empty.");
        }
        
        // FIRST CHECK IF THE BUYER GOT MONEY TO PAY OR NOT
        if($current_user_balance < $totalAmountToDeduct){
            throw new Exception("Insufficient funds brokie.");
        }

        // IF logic comes here then by RIGHT the buyer got enuf money.
        // Then now can carry on with the transaction.
        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                // Transfer funds to the seller's account
                $updateSellerStmt = $conn->prepare("UPDATE user_table SET funds = funds + ? WHERE user_id = ?");
                $updateSellerStmt->bind_param("di", $product['price'], $product['seller_id']);
                $updateSellerStmt->execute();
            }
        } else {
            throw new Exception("Cart is empty.");
        }
                        
        // Deduct the total amount from the buyer's funds
        $stmt = $conn->prepare("UPDATE user_table SET funds = funds - ? WHERE user_id = ?");
        $stmt->bind_param("di", $totalAmountToDeduct, $userId);
        $stmt->execute();



        // Commit the transaction
        $conn->commit();
        echo "Payment successful and funds transferred to sellers.";

        // Clear the cart after successful payment
        $stmt = $conn->prepare("DELETE FROM cart_table WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    } catch (Exception $e) {
        // Rollback the transaction in case of any error
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    }

    $conn->close();
} else {
    echo "Invalid request.";
}
?>

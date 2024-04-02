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
        
        // Get the current user balance first
        $stmt = $conn->prepare("SELECT funds FROM user_table WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows === 0) {
            throw new Exception("User account not found.");
        }
        $current_user_balance = $result->fetch_assoc()['funds'];
        
        // Retrieve items from the cart table for the current user
        $stmt = $conn->prepare("SELECT pt.product_id, pt.product_name, pt.price, pt.user_id AS seller_id, pt.cat_id FROM cart_table ct JOIN product_table pt ON ct.product_id = pt.product_id WHERE ct.user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $totalAmountToDeduct = 0;
        $products = [];

        if ($result->num_rows > 0) {
            while ($product = $result->fetch_assoc()) {
                $totalAmountToDeduct += $product['price'];
                $products[] = array(
                    'name' => $product['product_name'],
                    'price' => $product['price'],
                    'seller_id' => $product['seller_id'],
                    'cat_id' => $product['cat_id'],
                    'product_id' => $product['product_id']
                );
            }
        } else {
            throw new Exception("Cart is empty.");
        }
        
        // FIRST CHECK IF THE BUYER GOT MONEY TO PAY OR NOT
        if($current_user_balance < $totalAmountToDeduct){
            throw new Exception("Insufficient funds brokie.");
        }

        // Process each product in the cart
        foreach ($products as $product) {
            // Transfer funds to the seller's account
            $updateSellerStmt = $conn->prepare("UPDATE user_table SET funds = funds + ? WHERE user_id = ?");
            $updateSellerStmt->bind_param("di", $product['price'], $product['seller_id']);
            $updateSellerStmt->execute();
        }

        // Deduct the total amount from the buyer's funds
        $stmt = $conn->prepare("UPDATE user_table SET funds = funds - ? WHERE user_id = ?");
        $stmt->bind_param("di", $totalAmountToDeduct, $userId);
        $stmt->execute();

        // Insert transaction details into the transaction_history table
        $insertTransactionStmt = $conn->prepare("INSERT INTO transaction_table (buyer_id, seller_id, category_id, products_id, price) VALUES (?, ?, ?, ?, ?)");
        foreach ($products as $product) {
            $insertTransactionStmt->bind_param("iiisd", $userId, $product['seller_id'], $product['cat_id'], $product['product_id'], $product['price']);
            $insertTransactionStmt->execute();
        }
        $transactionId = $conn->insert_id;

        $stmt = $conn->prepare("SELECT email, username FROM user_table WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $userResult = $stmt->get_result();
        $userDetails = $userResult->fetch_assoc();

        $product_name = array_map(function ($product) {
            return $product['name'];
        }, $products);

        $transactionDetails = [
            'buyer_email' => $userDetails['email'],
            'buyer_name' => $userDetails['username'], 
            'products' => $product_name, 
            'total' => $totalAmountToDeduct, 
        ];  
        include 'receipt_email.php';

        // Commit the transaction
        $conn->commit();
        echo "Order placed successfully and funds transferred to sellers. <br> Please refer to your Transaction History for more details.";

        // Clear the cart after successful payment
        $stmt = $conn->prepare("DELETE FROM cart_table WHERE user_id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
    } catch (Exception $e) {
        // Rollback the transaction in case of any error
        $conn->rollback();
        echo "Transaction failed: " . $e->getMessage();
    } finally {
        // Close the database connection
        $conn->close();
    }
} else {
    echo "Invalid request.";
}
?>
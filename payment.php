<?php
session_start();

// Enable error reporting for debugging
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Check if user is logged in
if (!isset($_SESSION['userid'])) {
    // Redirect to login page or show an error
    die("You must be logged in to access this page.");
}

// Check if the form was submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Validate input
    if (isset($_POST['amount']) && is_numeric($_POST['amount']) && $_POST['amount'] > 0) {
        $amountToPay = floatval($_POST['amount']);
        $userId = $_SESSION['userid'];

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

        // Begin a transaction
        $conn->begin_transaction();

        try {
            // Check the user's current balance
            $stmt = $conn->prepare("SELECT funds FROM user_table WHERE user_id = ?");
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                $currentFunds = $row['funds'];

                // Check if the user has enough funds
                if ($currentFunds >= $amountToPay) {
                    // Deduct the amount from the user's funds
                    $newFunds = $currentFunds - $amountToPay;
                    $updateStmt = $conn->prepare("UPDATE user_table SET funds = ? WHERE user_id = ?");
                    $updateStmt->bind_param("di", $newFunds, $userId);
                    $updateStmt->execute();

                    // Commit the transaction
                    $conn->commit();
                    echo "Payment successful. New balance: $" . $newFunds;
                } else {
                    echo "Insufficient funds.";
                }
            } else {
                echo "User not found.";
            }
        } catch (Exception $e) {
            // An error occurred, roll back the transaction
            $conn->rollback();
            echo "An error occurred during payment.";
        }

        // Close the connection
        $conn->close();
    } else {
        echo "Invalid payment amount.";
    }
} else {
    // Not a POST request
    die("Invalid request.");
}
?>

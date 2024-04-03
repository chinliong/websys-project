<?php
session_start();

// Redirect to login if not logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Attempt to read database configuration
$config = parse_ini_file('/var/www/private/db-config.ini');
if (!$config) {
    $_SESSION['error_msg'] = "Failed to read database config file.";
    header('Location: profile.php');
    exit;
}

// Attempt to establish a database connection
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
if ($conn->connect_error) {
    $_SESSION['error_msg'] = "Connection failed: " . $conn->connect_error;
    header('Location: listings.php');
    exit;
}

// Check if the deletion form was submitted and password was provided
if (isset($_POST['product_id'])) {
    $product_id = $_POST['product_id'];

    $deleteSql = "DELETE FROM product_table WHERE product_id = ?";
    if ($deleteStmt = $conn->prepare($deleteSql)) {
        $deleteStmt->bind_param("i", $product_id);
        if ($deleteStmt->execute()) {
            // Deletion successful; logout and redirect to login page
            header('Location: listings.php');
            exit;
        } else {
            $_SESSION['error_msg'] = "Error deleting listing.";
        }
        $deleteStmt->close();
    } else {
        $_SESSION['error_msg'] = "Could not prepare statement for deletion.";
    }

    $conn->close();
    header('Location: listings.php');
    exit;
}

// Redirect back if accessed directly without form submission
header('Location: listings.php');
?>

<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

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
    header('Location: profile.php');
    exit;
}

// Check if the deletion form was submitted and password was provided
if (isset($_POST['confirm_deletion']) && isset($_POST['password'])) {
    $password = $_POST['password'];
    $userId = $_SESSION['userid'];

    // Fetch the hashed password from the database for verification
    $stmt = $conn->prepare("SELECT password FROM user_table WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($user = $result->fetch_assoc()) {
        if (password_verify($password, $user['password'])) {
            // Password is correct; proceed with user account deletion
            $deleteSql = "DELETE FROM user_table WHERE user_id = ?";
            if ($deleteStmt = $conn->prepare($deleteSql)) {
                $deleteStmt->bind_param("i", $userId);
                if ($deleteStmt->execute()) {
                    // Deletion successful; logout and redirect to login page
                    session_destroy();
                    header('Location: login.php?account_deleted=true');
                    exit;
                } else {
                    $_SESSION['error_msg'] = "Error deleting profile.";
                }
                $deleteStmt->close();
            } else {
                $_SESSION['error_msg'] = "Could not prepare statement for deletion.";
            }
        } else {
            $_SESSION['error_msg'] = "Incorrect password.";
        }
    } else {
        $_SESSION['error_msg'] = "User not found.";
    }
    $stmt->close();
    $conn->close();
    header('Location: profile.php');
    exit;
}

// Redirect back if accessed directly without form submission
header('Location: profile.php');
?>

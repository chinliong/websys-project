<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header('Location: login.php');
    exit;
}

// Database connection settings
global $errorMsg, $success;
// Create database connection.
$config = parse_ini_file('/var/www/private/db-config.ini');
if (!$config) {
    $errorMsg = "Failed to read database config file.";
    $success = false;
} else {
    $conn = new mysqli(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );
    // Check connection
    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
        $success = false;
    } else if (isset($_POST['confirm_deletion'])) {
        // User confirmed deletion
        $userId = $_SESSION['userid'];

        // Replace 'user_table' with your actual table name
        $sql = "DELETE FROM user_table WHERE user_id = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("i", $userId);
            if ($stmt->execute()) {
                // Successfully deleted user
                
                // Logout and destroy session
                $_SESSION = array();
                session_destroy();
                
                // If you're using session cookies, consider also deleting the session cookie
                if (ini_get("session.use_cookies")) {
                    $params = session_get_cookie_params();
                    setcookie(session_name(), '', time() - 42000,
                        $params["path"], $params["domain"],
                        $params["secure"], $params["httponly"]
                    );
                }

                // Redirect to login page
                header('Location: login.php');
                exit;
            } else {
                $errorMsg = "Error deleting profile.";
            }
            $stmt->close();
        } else {
            // This else block can help identify issues with the preparation of the SQL statement
            $errorMsg = "Could not prepare statement: " . $conn->error;
        }
        $conn->close();
    } else {
        // Show confirmation message
        echo '<h2>Are you sure you want to delete your profile?</h2>';
        echo '<form method="post">';
        echo '<input type="hidden" name="confirm_deletion" value="yes">';
        echo '<input type="submit" value="Yes, delete my profile" style="background-color: red; color: white; border: none; padding: 10px 20px; cursor: pointer;">';
        echo '</form>';
        echo '<p><a href="profile.php">Cancel</a></p>';
    }
}

if ($errorMsg) {
    echo "<p>$errorMsg</p>";
}
?>

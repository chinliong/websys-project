<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start(); 
if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true)) {
    header('Location: login.php');
    exit;
}

$config = parse_ini_file('/var/www/private/db-config.ini');
if (!$config) {
    $_SESSION['error_msg'] = "Failed to read database config file.";
    header('Location: listing.php');
    exit;
}

$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
if ($conn->connect_error) {
    $_SESSION['error_msg'] = "Connection failed: " . $conn->connect_error;
    header('Location: listing.php');
    exit;
}

$product_id = $_POST['userid'];
// Fetch user details for comparison or other logic as needed
$sql = "SELECT product_name, product_image, price, cat_id FROM product_table WHERE product_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $product_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->num_rows > 0 ? $result->fetch_assoc() : null;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $oldPassword = $_POST['old_password'];
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $newPassword = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_DEFAULT) : null;

    // Fetch the current password hash from the database
    $stmt = $conn->prepare("SELECT password FROM user_table WHERE user_id = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    
    if ($user && password_verify($oldPassword, $user['password'])) {
        // Proceed with updating the user's profile
        $query = "UPDATE user_table SET username = ?, email = ?".($newPassword ? ", password = ?" : "")." WHERE user_id = ?";
        $stmt = $conn->prepare($query);
        
        if ($newPassword) {
            $stmt->bind_param("sssi", $newUsername, $newEmail, $newPassword, $userId);
        } else {
            $stmt->bind_param("ssi", $newUsername, $newEmail, $userId);
        }
        
        if ($stmt->execute()) {
            $_SESSION['success_msg'] = "Profile updated successfully.";
        } else {
            $_SESSION['error_msg'] = "Error updating profile.";
        }
    } else {
        $_SESSION['error_msg'] = "Incorrect old password.";
    }

    $stmt->close();
    header('Location: profile.php'); // Redirect back to profile.php
    exit;
}

$conn->close();
?>

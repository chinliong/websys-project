<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();

if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo '<h1>You are not logged in.</h1>';
    echo '<p><a href="login.php">Login here</a></p>';
    exit;
}

$errorMsg = '';
$successMsg = '';

$config = parse_ini_file('/var/www/private/db-config.ini');
if (!$config) {
    $errorMsg = "Failed to read database config file.";
} else {
    $conn = new mysqli(
        $config['servername'],
        $config['username'],
        $config['password'],
        $config['dbname']
    );

    if ($conn->connect_error) {
        $errorMsg = "Connection failed: " . $conn->connect_error;
    } elseif ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
        $username = $conn->real_escape_string(trim($_POST["username"]));
        $email = $conn->real_escape_string(trim($_POST["email"]));
    
        $sql = "UPDATE user_table SET username = ?, email = ? WHERE user_id = ?";
        
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ssi", $username, $email, $_SESSION['userid']);
            
            if ($stmt->execute()) {
                $successMsg = "Profile updated successfully.";
            } else {
                $errorMsg = "Oops! Something went wrong. Please try again later.";
            }
            
            $stmt->close();
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Update Profile</title>
</head>
<body>
    <?php if ($errorMsg) echo "<p>Error: $errorMsg</p>"; ?>
    <?php if ($successMsg) echo "<p>$successMsg</p>"; ?>
    <!-- Form for updating profile -->
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <label for="username">Username:</label>
        <input type="text" name="username" id="username" required>
        <label for="email">Email:</label>
        <input type="email" name="email" id="email" required>
        <input type="submit" name="update_profile" value="Update Profile">
    </form>
</body>
</html>

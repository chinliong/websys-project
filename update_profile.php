<?php
session_start();
// Ensure the user is logged in
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    echo '<h1>You are not logged in.</h1>';
    echo '<p><a href="login.php">Login here</a></p>';
    exit;
}

// // Database connection settings
// $host = 'localhost'; // or your host
// $dbUsername = 'your_username'; // your database username
// $dbPassword = 'your_password'; // your database password
// $dbName = 'your_database_name'; // your database name

// // Create a new MySQLi connection
// $mysqli = new mysqli($host, $dbUsername, $dbPassword, $dbName);

// // Check connection
// if ($mysqli->connect_error) {
//     die("Connection failed: " . $mysqli->connect_error);
// }

// Initialize variables
$errorMsg = '';
$successMsg = '';

// Check if the form is submitted for profile update
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['update_profile'])) {
    // Extract and sanitize input
    $username = $mysqli->real_escape_string(trim($_POST["username"]));
    $email = $mysqli->real_escape_string(trim($_POST["email"]));
    
    // Prepare an update statement
    $sql = "UPDATE user_table SET username = ?, email = ? WHERE user_id = ?";
    
    if ($stmt = $mysqli->prepare($sql)) {
        // Bind variables to the prepared statement as parameters
        $stmt->bind_param("ssi", $username, $email, $_SESSION['userid']);
        
        // Attempt to execute the prepared statement
        if ($stmt->execute()) {
            $successMsg = "Profile updated successfully.";
        } else {
            $errorMsg = "Oops! Something went wrong. Please try again later.";
        }
        
        // Close statement
        $stmt->close();
    }
}

// Close database connection
$mysqli->close();
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

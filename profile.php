<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

session_start();
if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true)) {
    echo '<h1> You are not logged in. </h1>';
    echo '<p> <a href="login.php">Login here</a> </p>';
    exit;
}

$config = parse_ini_file('/var/www/private/db-config.ini');
if (!$config) {
    die("Failed to read database config file.");
}
$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle profile update
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $newUsername = $_POST['username'];
    $newEmail = $_POST['email'];
    $stmt = $conn->prepare("UPDATE user_table SET username = ?, email = ? WHERE user_id = ?");
    $stmt->bind_param("ssi", $newUsername, $newEmail, $_SESSION['userid']);
    if ($stmt->execute()) {
        echo "<p>Profile updated successfully.</p>";
    } else {
        echo "<p>Error updating profile.</p>";
    }
    $stmt->close();
}

$stmt = $conn->prepare("SELECT * FROM user_table WHERE user_id = ?");
$stmt->bind_param("i", $_SESSION['userid']);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
}
$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ferris wheel</title>
    <?php include 'inc/head.inc.php'; ?>
</head>
<body>
    <?php include 'inc/nav.inc.php'; ?>
    <?php include 'inc/header.inc.php'; ?>

    <main class="container">
        <section id="User Profile">
            <div class="row">
                <?php if (isset($row)) {
                    echo '<article class="col-md-6">';
                    echo "<h2>Username: " . $row["username"] . "</h2>";
                    echo "<h2>Email: " . $row["email"] . "</h2>";
                    echo '</article>';
                } ?>
            </div>
            <!-- Profile Update Form -->
            <form method="post">
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <input type="submit" value="Update Profile">
            </form>
        </section>
        <?php include 'inc/footer.inc.php'; ?>
    </main>
</body>
</html>

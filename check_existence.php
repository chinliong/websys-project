<?php
// Connection variables
$host = "your_host";
$dbname = "your_dbname";
$username = "your_username";
$password = "your_password";

// Connect to the database
$conn = new mysqli($host, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get the type (username or email) and value from AJAX
$type = $_POST['type'];
$value = $_POST['value'];
$response = "not_exists";

if ($type == "uname") {
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
} elseif ($type == "email") {
    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ?");
}

$stmt->bind_param("s", $value);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $response = "exists";
}

echo $response;

$conn->close();
?>

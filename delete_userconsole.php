<?php
session_start();
require 'db_con.php'; // Adjust the path as needed

if (!isset($_GET['user_id'])) {
    header('Location: console.php');
    exit;
}

$user_id = $_GET['user_id'];

// SQL to delete the user
$sql = "DELETE FROM user_table WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
if ($stmt->execute()) {
    echo "User deleted successfully.";
} else {
    echo "Error deleting user: " . $conn->error;
}
$stmt->close();
$conn->close();

header('Location: console.php');
exit;
?>

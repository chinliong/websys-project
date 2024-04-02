<?php
session_start();
include 'db_con.php'; // Ensure this path is correct

// Process the update when the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['user_id'])) {
    $user_id = $_POST['user_id'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $funds = $_POST['funds']; // Retrieve the funds from the form
    $role = $_POST['role'];
    // Perform input validation and sanitization here

    $stmt = $conn->prepare("UPDATE user_table SET username = ?, email = ?, funds = ?, user_role = ? WHERE user_id = ?");
    $stmt->bind_param("ssdsi", $username, $email, $funds, $role, $user_id); // 'd' for double (funds)
    
    if ($stmt->execute()) {
        header('Location: console.php?message=User updated successfully');
        exit;
    } else {
        echo "Error updating record: " . $conn->error;
    }

    $stmt->close();
}

// If it's not a POST request, or after the update, load the user's data for editing
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];
    $stmt = $conn->prepare("SELECT * FROM user_table WHERE user_id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) { exit('User not found.'); }
    $user = $result->fetch_assoc();
    $stmt->close();
} else {
    header('Location: console.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <?php include 'inc/head.inc.php'; ?>
</head>
<body>
    <?php include "inc/nav.inc.php"; ?>
    <main class="container">
        <h2>Edit User</h2>
        <form action="edit_userconsole.php" method="post">
            <input type="hidden" name="user_id" value="<?= htmlspecialchars($user['user_id']); ?>">
            <div class="form-group">
                <label for="username">Username:</label>
                <input type="text" name="username" id="username" value="<?= htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" value="<?= htmlspecialchars($user['email']); ?>" required>
            </div>
            <div class="form-group">
                <label for="role">Role:</label>
                <select name="role" id="role" class="form-control">
                    <option value="a" <?= $user['user_role'] == 'a' ? 'selected' : ''; ?>>Admin</option>
                    <option value="u" <?= $user['user_role'] == 'u' ? 'selected' : ''; ?>>User</option>
                </select>
            </div>
            <div class="form-group">
                <label for="funds">Funds:</label>
                <input type="number" step="0.01" name="funds" id="funds" value="<?= htmlspecialchars($user['funds']); ?>" required>
            </div>
            <div class="form-group">
                <label for="status">Status:</label>
                <input type="text" id="status" value="<?= $user['status'] == 1 ? 'Verified' : 'Not Verified'; ?>" class="form-control" disabled>
            </div>
            <button type="submit" class="btn btn-primary">Update User</button>
        </form>
    </main>
    <?php include "inc/footer.inc.php"; ?>
</body>
</html>

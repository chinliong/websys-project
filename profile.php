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
    header('Location: profile.php');
    exit;
}

$conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
if ($conn->connect_error) {
    $_SESSION['error_msg'] = "Connection failed: " . $conn->connect_error;
    header('Location: profile.php');
    exit;
}

// Prepare the SELECT statement to fetch user data
$userId = $_SESSION['userid'];
$sql = "SELECT username, email FROM user_table WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    // Handle case where user data is not found
    $row = null;
    echo '<p>User data not found.</p>';
}

$stmt->close();
$conn->close();

// Check for messages
$successMsg = isset($_SESSION['success_msg']) ? $_SESSION['success_msg'] : '';
$errorMsg = isset($_SESSION['error_msg']) ? $_SESSION['error_msg'] : '';

// Clear the messages from the session to avoid displaying them again on refresh
unset($_SESSION['success_msg'], $_SESSION['error_msg']);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Profile</title>
    <?php include 'inc/head.inc.php'; ?>
</head>

<body>
    <?php include 'inc/nav.inc.php'; ?>
    <?php include 'inc/header.inc.php'; ?>

    <main class="container">
        <section id="User Profile">
            <!-- Success and Error Messages -->
            <div class="alert-area">
                <?php if ($successMsg) : ?>
                    <div class="alert alert-success" role="alert"><?= htmlspecialchars($successMsg); ?></div>
                <?php endif; ?>
                <?php if ($errorMsg) : ?>
                    <div class="alert alert-danger" role="alert"><?= htmlspecialchars($errorMsg); ?></div>
                <?php endif; ?>
            </div>

            <!-- Profile Information -->
            <div class="card mb-4">
                <div class="card-header">
                    <h4 class="mb-0">Profile Information</h4>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item"><i class="fas fa-user profile-icon"></i>Username: <strong><?= htmlspecialchars($row['username']); ?></strong></li>
                    <li class="list-group-item"><i class="fas fa-envelope profile-icon"></i>Email: <strong><?= htmlspecialchars($row['email']); ?></strong></li>
                </ul>
                <div class="card-body">
                    <button id="editProfileBtn" class="btn btn-custom">Edit Profile</button>
                    <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteConfirmationModal">
                        Delete Profile
                    </button>

                    <!-- Deletion Confirmation Modal -->
                    <div class="modal fade" id="deleteConfirmationModal" tabindex="-1" aria-labelledby="deleteConfirmationModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="deleteConfirmationModalLabel">Confirm Profile Deletion</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    To delete your profile, please confirm your password:
                                    <form id="deleteProfileForm" action="delete_profile.php" method="post">
                                        <div class="mb-3">
                                            <label for="passwordConfirmation" class="form-label">Password:</label>
                                            <input type="password" class="form-control" id="passwordConfirmation" name="password" required>
                                        </div>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="submit" form="deleteProfileForm" class="btn btn-danger" name="confirm_deletion" value="yes">Delete Profile</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    </form>
                </div>
            </div>

            <!-- Edit Profile Form -->
            <div id="editProfileForm">
                <form action="update_profile.php" method="post" class="card p-3">
                    <div class="form-group">
                        <label for="username">Username:</label>
                        <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($row['username']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="email">Email:</label>
                        <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($row['email']); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="old_password">Old Password:</label>
                        <input type="password" class="form-control" id="old_password" name="old_password" required>
                    </div>
                    <div class="form-group">
                        <label for="password">New Password (optional):</label>
                        <input type="password" class="form-control" id="password" name="password">
                    </div>
                    <button type="submit" class="btn btn-custom">Update Profile</button>
                </form>
            </div>
        </section>
        <?php include 'inc/footer.inc.php'; ?>
    </main>

    <script>
        $(document).ready(function() {
            $("#editProfileBtn").click(function() {
                $("#editProfileForm").slideToggle();
            });
        });
    </script>
</body>

</html>
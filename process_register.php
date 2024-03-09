<!DOCTYPE html>
<html lang="en">
<head>
<title>Ferris wheel</title>
    <?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    ?>
    <?php 
        include 'inc/head.inc.php';
    ?>
</head>
<body>
<main>
<?php
include "inc/nav.inc.php";
?>
<?php
$email = $errorMsg = "";
$success = true;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["uname"])) {
        $errorMsg .= "Username is required.<br>";
        $success = false;
    } else {
        $uname = sanitize_input($_POST["uname"]);
    }

    if (empty($_POST["email"])) {
        $errorMsg .= "Email is required.<br>";
        $success = false;
    } else {
        $email = sanitize_input($_POST["email"]);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errorMsg .= "Invalid email format.<br>";
            $success = false;
        }
    }

    if (empty($_POST["pwd"])) {
        $errorMsg .= "Password is required.<br>";
        $success = false;
    } else {
        $password = $_POST["pwd"];
    }

    if (empty($_POST["pwd_confirm"])) {
        $errorMsg .= "Confirm password is required.<br>";
        $success = false;
    } else {
        $confirmPassword = $_POST["pwd_confirm"];
    }

    if ($success) {
        // Additional check to make sure password and confirm password match.
        if ($password !== $confirmPassword) {
            $errorMsg .= "Password and confirm password do not match.<br>";
            $success = false;
        } else {
            // Hash the password using password_hash()
            $pwd_hashed = password_hash($password, PASSWORD_DEFAULT);
            // Perform database operations or other actions here (not included in this example).
            
        }
    }
    if ($success)
    {
    saveMemberToDB($uname, $email, $pwd_hashed, $errorMsg, $success);
    echo "<h4>Registration successful!</h4>";
    echo "<p>Email: " . $email;
    echo "<br>  ";
    echo "Thank you for signing up ". $fname;
    }
}
else
{
echo '<form action="register.php" method="post">';
echo "<h3>Oops!</h3>";
echo "<h4>The following input errors were detected:</h4>";
echo "<p>" . $errorMsg . "</p>";
echo '<button type="submit" class="btn btn-danger">Return to Sign Up</button>';

}
/*
* Helper function that checks input for malicious or unwanted content.
*/

function saveMemberToDB($uname, $email, $pwd_hashed, $errorMsg, $success)
{
    
//global $fname, $lname, $email, $pwd_hashed, $errorMsg, $success;
// Create database connection.
$config = parse_ini_file('/var/www/private/db-config.ini');
if (!$config)
{
$errorMsg = "Failed to read database config file.";
echo "<h4>bad thign</h4>";
$success = false;
}
else
{
$conn = new mysqli(
$config['servername'],
$config['username'],
$config['password'],
$config['dbname']
);
// Check connection
if ($conn->connect_error)
{
$errorMsg = "Connection failed: " . $conn->connect_error;
$success = false;
}
else
{

?>
<script type="text/javascript">
    console.log("Connected to database");
</script>
<?php
// Prepare the statement:
$sql = "INSERT INTO user_table 
(username, email, password) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

// Bind & execute the query statement:
$stmt->bind_param("sss", $uname, $email, $pwd_hashed);
if (!$stmt->execute())
{
$errorMsg = "Execute failed: (" . $stmt->errno . ") " .
$stmt->error;
$success = false;
}

$stmt->close();
}
$conn->close();
}
}
function sanitize_input($data)
{
$data = trim($data);
$data = stripslashes($data);
$data = htmlspecialchars($data);
return $data;
}
?>
<?php
include "inc/footer.inc.php";
?>
</main>
</body>
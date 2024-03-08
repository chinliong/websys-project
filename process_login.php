<!DOCTYPE html>
<html lang="en">
<head>
<title>Ferris Wheel</title>
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
$uname = $errorMsg = "";
$success = true;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (empty($_POST["uname"])) {
        $errorMsg .= "Username is required.<br>";
        $success = false;
    } else {
        $uname = sanitize_input($_POST["uname"]);
    }

    if (empty($_POST["pwd"])) {
        $errorMsg .= "Password is required.<br>";
        $success = false;
    } else {
        $password = $_POST["pwd"];
    }

    if ($success) {
        $pwd_hashed = password_hash($password, PASSWORD_DEFAULT);
        authenticateUser();
        
    }

}
if ($success)
{

echo '<form action="index.php" method="post">';
echo "<h4>Login successful!</h4>";
echo "<p>Welcome back, " . $uname;
echo '<button type="submit" class="btn btn-success">Return to Home</button>';
echo "<br>  ";    
}
else
{
echo '<form action="login.php" method="post">';
echo "<h3>Oops!</h3>";
echo "<h4>The following input errors were detected:</h4>";
echo "<p>" . $errorMsg . "</p>";
echo '<button type="submit" class="btn btn-warning">Return to Login</button>';
}
/*
* Helper function to authenticate the login.
*/
function authenticateUser()
{
global $uname, $pwd_hashed, $errorMsg, $success;
// Create database connection.
$config = parse_ini_file('/var/www/private/db-config.ini');
if (!$config)
{
$errorMsg = "Failed to read database config file.";
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
// Prepare the statement:
$stmt = $conn->prepare("SELECT * FROM user_table WHERE username=?");
// Bind & execute the query statement:
$stmt->bind_param("s", $uname);
$stmt->execute();
$result = $stmt->get_result();
if ($result->num_rows > 0){
// Note that email field is unique, so should only have
// one row in the result set.
$row = $result->fetch_assoc();
$pwd_hashed = $row["password"];
// Check if the password matches:
if (!password_verify($_POST["pwd"], $pwd_hashed)){
// Don't be too specific with the error message - hackers don't
// need to know which one they got right or wrong. :)
$errorMsg = "Email not found or password doesn't match...";
$success = false;
}
else{
// Start the session:
$_SESSION['loggedin'] = true;
}
}
else
{
$errorMsg = "Email not found or password doesn't match...";
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
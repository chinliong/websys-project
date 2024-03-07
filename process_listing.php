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
$success = true;
$errorMsg = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $filename = $_FILES['pimage']['name'];
    $filetmpname = $_FILES['pimage']['tmp_name'];
    $filetype = $_FILES['pimage']['type'];
    $filesize = $_FILES['pimage']['size'];
    $folder = "./images/" . $filename;

    if (empty($_POST["pname"])) {
        $errorMsg .= "Product name is required.<br>";
        $success = false;
    } else {
        $pname = sanitize_input($_POST["pname"]);
    }

    if (empty($_POST["price"])) {
        $errorMsg .= "Price is required.<br>";
        $success = false;
    } else {
        $price = sanitize_input($_POST["price"]);
    }

    if(isset($_FILES['pimage'])) {
        $errors= array();
        $file_name = $_FILES['pimage']['name'];
        $file_size = $_FILES['pimage']['size'];
        $file_tmp = $_FILES['pimage']['tmp_name'];
        $file_type = $_FILES['pimage']['type'];
        $folder = "./images/" . $filename;
    } else{
        $errorMsg .= "Please upload an image.<br>";
        $success = false;
    }

    placeListing($folder, $file_tmp, $file_name, $pname, $price, $errorMsg, $success);
}
if ($success)
{

echo '<form action="index.php" method="post">';
echo "<h4>Listing Successful!</h4>";
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
* Helper function to place the listing.
*/
function placeListing($folder, $tmpname, $pimage, $pname, $price, $errorMsg, $success)
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
$sql = "INSERT INTO product_table
(product_name, product_image, price) VALUES (?, ?, ?)";
$stmt = $conn->prepare($sql);

// Bind & execute the query statement:
$stmt->bind_param("sss", $pname, $pimage, $price);
?>

<script type="text/javascript">
    console.log("Executed statement");
</script>
<?php
if (!$stmt->execute())

{
$errorMsg = "Execute failed: (" . $stmt->errno . ") " .
$stmt->error;
$success = false;
}

if (!(move_uploaded_file($tmpname, $folder))) {
    $errorMsg = "Failed to upload image";
    $success = false;
}
?>
<script type="text/javascript">
    console.log("Idk what but there's some error here");
</script>
<?php
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
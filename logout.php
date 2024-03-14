<?php
//Check if user logged in
    session_start();
    if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'])) {
        echo '<h1> You are not logged in bro??????? </h1>';
        echo '<p> <a href="login.php">Login here if u wanna log out or smth</a> </p>';
    } else {
        $_SESSION = array();
        session_destroy();

        // Redirect to index.php
        header("Location: index.php");
        exit; // Ensure the script stops executing after redirection
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferris Wheel</title>
    <?php 
    include 'inc/head.inc.php';
    ?>
</head>
<body>
    <?php
    include "inc/nav.inc.php";
    ?>

    
    <main class = "container">

    <?php include 'inc/footer.inc.php'; ?>
    </main>
</body>
</html>
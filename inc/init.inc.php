<?php
    session_start();
    if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'])) {
        echo '<h1> You are not logged in. </h1>';
        echo '<p> <a href="login.php">Login here</a> </p>';
        header('Location: ../login.php');
        exit;
    }
?>
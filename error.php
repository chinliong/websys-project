<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
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
    <?php 
        include 'inc/nav.inc.php'; 
    ?>
    <h1>Oops! Either something went wrong or you don't belong here...</h1>
    <p>I'm guessing you don't belong here...</p>

    <?php
        include 'inc/footer.inc.php';
    ?>
</body>
</html>
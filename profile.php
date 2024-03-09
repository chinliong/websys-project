<?php
//Check if user logged in
    session_start();
    if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'])) {
        echo '<h1> You are not logged in. </h1>';
        echo '<p> <a href="login.php">Login here</a> </p>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Ferris wheel</title>
    <?php 
        
        include 'inc/head.inc.php';
    ?>
</head>
<body>
    <?php 
        include 'inc/nav.inc.php'; 
    ?>

    <?php 
        include 'inc/header.inc.php'; 
    ?>
            
    <main class="container">

        <section id="User Profile">
            <div class="row">
            <?php

                //Boring back-end SQL connection stuff
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
                //Prep and execute statement here
                $stmt = $conn->prepare("SELECT * FROM user_table WHERE user_id = ?");
                $stmt->bind_param("i", $_SESSION['userid']);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0){
                $row = $result->fetch_assoc();

                // [FRONT-END] Display the user's profile information here :-)
                echo '<article class="col-md-6">';
                echo "<h2>Username: " . $row["username"] . "</h2>";
                echo "<h2>Email: " . $row["email"] . "</h2>";
                }
                $stmt->close();
                }
                $conn->close();
                }
            ?>
            </div>

        </section>
           <?php include 'inc/footer.inc.php'; ?>
    </main>
</body>
</html>
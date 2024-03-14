<?php
 include 'inc/head.init.php';
?>
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
<title>Ferris Wheel</title>    
</head>

<body>
<?php
    include "inc/nav.inc.php";
    ?>
<?php 
    include 'inc/header.inc.php';
    ?>
    <main class="container">
        <?php
    echo "<h1>Shopping Cart Page</h1>";
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
        $user_id_cart_name = $_SESSION['userid'];
        $stmt = $conn->prepare("SELECT cart_table.*, product_table.* FROM cart_table 
        RIGHT JOIN product ON product_table.cat_id = product_category.cat_id 
        WHERE product_name LIKE ? AND product_table.cat_id = ?");
        $stmt->bind_param("i", $user_id_cart_name);

        if (!$stmt->execute())
        {
        $errorMsg = "Execute failed: (" . $stmt->errno . ") " .
        $stmt->error;
        $success = false;

        }

        //[Front-end] display search results
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo '<article class="col-md-4">';
            echo '<img src="/images/' . $row["product_image"] . '" alt="' . $row["product_name"] . '" class="img-fluid">';
            echo '<h3>' . $row["product_name"] . '</h3>';
            echo '<p>$' . $row["price"] . '</p>';
            echo '<p>Category: ' . $row["cat_id"] . '</p>';
            echo '<p>Seller: ' . $row["seller_name"] . '</p>';
            echo '</article>';
        }
    $stmt->close();
    }
    $conn->close();
    }
    
    include "inc/footer.inc.php";
    ?>
</body>
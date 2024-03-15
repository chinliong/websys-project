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
        RIGHT JOIN product_table ON product_table.product_id = cart_table.product_id 
        WHERE user_id = ?;");
        $stmt->bind_param("i", $user_id_cart_name);

        if (!$stmt->execute())
        {
        $errorMsg = "Execute failed: (" . $stmt->errno . ") " .
        $stmt->error;
        $success = false;

        }

        //[Front-end] display search results
        $result = $stmt->get_result();
        echo '<section class="shopping-cart">';
        while ($row = $result->fetch_assoc()) {
            echo '<article class="product">';
            echo '<h2 class="product-title">' . $row["product_name"] . '</h2>';
            echo '<p class="product-price">Price: $' . $row["price"] . '</p>';
            echo '<p class="product-category">Category: ' . $row["cat_id"] . '</p>';
            echo '<p class="product-seller">Seller: ' . $row["seller_name"] . '</p>';
            echo '</article>';
        }
        echo '</section>';
    $stmt->close();
    }
    $conn->close();
    }
    
    include "inc/footer.inc.php";
    ?>
</body>
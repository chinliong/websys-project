<!DOCTYPE html>
<html lang="en">
<head>
    <?php       
        include 'inc/head.inc.php';
        include 'inc/header.inc.php';
    ?>
    <script src="js/async.js"></script>

</head>
<body>
    <main class="container">
        <?php 
            include 'inc/nav.inc.php'; 
            include 'db_con.php';
            session_start();
            $product_id = $_GET['id'];
            echo "<script>console.log('we are down');</script>";
            $sql = $conn->prepare("SELECT p.product_name, p.product_image, p.price, u.username 
            FROM product_table p 
            INNER JOIN user_table u ON p.user_id = u.user_id 
            WHERE p.product_id = ?");
            $sql->bind_param("i", $product_id);
            $sql->execute();
            $result = $sql->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }
                foreach ($products as $product) {
                    echo "<div class='product-page-image'>";
                    echo "<img src='/images/" . htmlspecialchars($product['product_image']) . "' class='lcard' alt='" . htmlspecialchars($product['product_name']) . "' />";
                    echo "<div class='product-details'>";
                    echo "<h2>" . htmlspecialchars($product['product_name']) . "</h2>";
                    echo "<p>Price: $" . htmlspecialchars($product['price']) . "</p>";
                    echo "<p>Seller: " . htmlspecialchars($product['username']) . "</p>";
                    if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] && $_SESSION['userid'] != $row["user_id"]) {
                        echo '<button type="button" class="btn btn-primary add-to-cart" data-product-id="' . $product_id . '">Add to Cart</button>';
                    } else {
                        // User is not logged in, prompt to log in
                        echo '<a href="login.php" class="btn btn-primary">Log in to Purchase</a>';
                    }
                    echo "</div>";
                    echo "</div>";

                }
            } else {
                echo "0 results";
            }
            $conn->close();
            include 'inc/footer.inc.php';
        ?>
    </main>
</body>
</html>
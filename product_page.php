<!DOCTYPE html>
<html lang="en">
<head>
    <?php       
        include 'inc/head.inc.php';
    ?>
</head>
<body>
    <main class="container">
        <?php 
            include 'inc/nav.inc.php'; 
            include 'db_con.php';
            include 'inc/header.inc.php';
            $product_id = $_GET['id'];
            echo "<script>console.log('we are down');</script>";
            $sql = $conn->prepare("SELECT product_name, product_image, price, user_id FROM product_table WHERE product_id = ?");
            $sql->bind_param("i", $product_id); 
            $sql->execute();
            $result = $sql->get_result();

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    $products[] = $row;
                }
                foreach ($products as $product) {
                    echo "<div class='product-page-image'>";
                    echo "<img src='/images/" . htmlspecialchars($product['product_image']) . "' alt='" . htmlspecialchars($product['product_name']) . "' />";
                    echo "<div class='product-details'>";
                    echo "<h2>" . htmlspecialchars($product['product_name']) . "</h2>";
                    echo "<p'>Price: $" . htmlspecialchars($product['price']) . "</p>";
                    echo "<p>Seller: " . htmlspecialchars($product['user_id']) . "</p>";
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
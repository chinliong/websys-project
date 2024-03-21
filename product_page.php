<!DOCTYPE html>
<html lang="en">
<head>
<link rel="stylesheet" href="css/main.css">
<title>Shoe-seller</title>
    <?php       
        include 'inc/head.inc.php';
    ?>
</head>
<body>
    <?php 
        include 'inc/nav.inc.php'; 
        include 'db_con.php';

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
            echo "<div class='product'>";
            echo "<h2>" . htmlspecialchars($product['product_name']) . "</h2>";
            echo "<img src='/images/" . htmlspecialchars($product['product_image']) . "' alt='" . htmlspecialchars($product['product_name']) . "' />";
            echo "<p class='product_price'>Price: $" . htmlspecialchars($product['price']) . "</p>";
            echo "<p class='product_seller'>Seller: " . htmlspecialchars($product['user_id']) . "</p>";
            echo "</div>";
        }
    } else {
        echo "0 results";
    }
    $conn->close();
    include 'inc/footer.inc.php';
?>


</body>
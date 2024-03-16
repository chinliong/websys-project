<?php
include 'db_con.php';

$product_id = $_GET['id'];
$sql = $db->prepare("SELECT product_name, product_image, price, seller_name FROM product_table WHERE product_id = ?");
$sql->bind_param("i", $product_id); 
$sql->execute();
$result = $conn->query($sql);

$product_details = array();
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
    foreach ($products as $product) {
        echo "<div class='product'>";
        echo "<h2>" . htmlspecialchars($product['product_name']) . "</h2>";
        echo "<img src='/images/" . htmlspecialchars($product['product_image']) . "' alt='" . htmlspecialchars($product['product_name']) . "' />";
        echo "<p>Price: $" . htmlspecialchars($product['price']) . "</p>";
        echo "<p>Seller: " . htmlspecialchars($product['seller_name']) . "</p>";
        echo "</div>";
    }
} else {
    echo "0 results";
}
$conn->close();
<?php
    session_start();
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
        header("location: login.php");
        exit;
    }

    include 'db_con.php';

    $have_products = false;
    $stmt = $conn->prepare("SELECT product_id, product_name, product_image, price, cat_name FROM product_table INNER JOIN product_category ON product_table.cat_id = product_category.cat_id where user_id = ?");
    $stmt->bind_param("i", $_SESSION['userid']);
    $stmt->execute();
    $products_of_viewing_user_table = $stmt->get_result();
    
    if($products_of_viewing_user_table){
        $products_of_viewing_user = $products_of_viewing_user_table->fetch_all(MYSQLI_ASSOC);
        if (empty($products_of_viewing_user)) {
            $have_products = true;
        }
    } else {
        echo "<h1>Error fetching product table</h1>";
    }
    $stmt->close();  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ferriswheel</title>
    <?php include 'inc/head.inc.php'; ?>
</head>
<body>
    <?php include 'inc/nav.inc.php'; ?>
    <main id="container">
        <h1> Manage My Listings </h1>
        <section>
        <?php
            if($have_products){
                echo "<h2> You have no products listed. </h2>";
            } else {
                echo "<div class='table-responsive product-management'>";
                echo "<table class='table table-hover'>";
                echo "<thead class='thead-light'>";
                echo "<tr>";
                echo "<th>Product Name</th>";
                echo "<th>Product Image</th>";
                echo "<th>Price</th>";
                echo "<th>Category</th>";
                echo "<th>Actions</th>";
                echo "</tr>";
                echo "</thead>";
                echo "<tbody>";
                foreach($products_of_viewing_user as $product){
                    echo "<tr>";
                    echo "<td class='product-name'>" . $product['product_name'] . "</td>";
                    echo "<td><img src='images/" . $product['product_image'] . "' alt='" . $product['product_name'] . "' class='listing-img'></td>";
                    echo "<td>&dollar;" . $product['price'] . "</td>";
                    echo "<td>" . $product['cat_name'] . "</td>";
                    echo "<td>";
                    echo "<form action='delete_listing.php' method='post'>
                            <label for='product_id_" . $product['product_id'] . "' class='visually-hidden'>Delete " . $product['product_id'] . "</label>
                            <input type='hidden' id='product_id_" . $product['product_id'] . "' name='product_id' value='" . $product['product_id'] . "'>
                            <button type='submit'>Delete</button>
                        </form>";
                    echo "<form action='edit_listing.php' method='post'>
                        <label for='product_id_" . $product['product_id'] . "' class='visually-hidden'>Edit " . $product['product_id'] . "</label>
                        <input type='hidden' id='product_id_" . $product['product_id'] . "' name='product_id' value='" . $product['product_id'] . "'>
                        <button type='submit'>Delete</button>
                    </form>";
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            }
        ?>
        </section>  
    </main>
</body>
</html>
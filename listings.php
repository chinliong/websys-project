<?php
    session_start();
    if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
        header("location: login.php");
        exit;
    }

    include 'db_con.php';


    $have_products = false;
    $stmt = $conn->prepare("SELECT product_table.cat_id, product_id, product_name, product_image, price, cat_name FROM product_table INNER JOIN product_category ON product_table.cat_id = product_category.cat_id where user_id = ?");
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
    $categories = [];
    
//this loop is for the chart
foreach ($products_of_viewing_user as $product) {
    $categories[$product['cat_name']] = isset($categories[$product['cat_name']]) ? $categories[$product['cat_name']] + 1 : 1;
}

    //
    $stmt = $conn->prepare("SELECT * from product_category");
    $stmt->execute();
    $category_results = $stmt->get_result();

    $stmt->close();
    $conn->close();
?>


<!-- front end -->
<!DOCTYPE html>
<html lang="en">
<head>
    
    <title>Ferriswheel</title>

    <?php
    include 'inc/head.inc.php';             
            ?>
    </head>
<body>
    <?php include 'inc/nav.inc.php'; 
        include 'inc/header.inc.php'; 
        ?>
    
    <main id="container">
        <h1> Manage My Listings </h1>
        <div>
        <?php
            echo "<div class='container'>";
            if($have_products){
                echo "<h2> You have no products listed. </h2>";
            } else {
                // Mobile phone table
                echo "<div class='d-block d-sm-none'>"; // Only visible on XS screens
                echo "<div class='table-responsive'>";
                echo "<table class='table table-smaller'>";
                echo "<thead>";
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
                    echo "<td >" . $product['product_name'] . "</td>";
                    echo "<td ><img src='images/" . $product['product_image'] . "' alt='" . $product['product_name'] . "' class='listing-img-smallertable'></td>";
                    echo "<td >&dollar;" . $product['price'] . "</td>";
                    echo "<td>" . $product['cat_name'] . "</td>";
                    echo "<td>";
                    echo "<form action='delete_listing.php' method='post'>";
echo "<input type='hidden' name='product_id_small' value='" . $product['product_id'] . "'>";
echo "<button class='delete-button' type='submit'>Delete</button>";
echo "</form>";

                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "</div>";

                // Main table for larger screens
                echo "<div class='d-none d-sm-block'>"; // Hidden on XS screens
                echo "<div class='table-responsive'>";
                echo "<table class='table'>";
                echo "<thead>";
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
                    echo "<td class='product-name'><img src='images/" . $product['product_image'] . "' alt='" . $product['product_name'] . "' class='listing-img-normal'></td>";
                    echo "<td class='product-name'>&dollar;" . $product['price'] . "</td>";
                    echo "<td class='product-name'>" . $product['cat_name'] . "</td>";
                    echo "<td class='product-name'>";
                    echo "<form action='delete_listing.php' method='post'>";
echo "<input type='hidden' name='product_id_small' value='" . $product['product_id'] . "'>";
echo "<button class='delete-button' type='submit'>Delete</button>";
echo "</form>";

                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
                echo "</div>";
            }
        ?>
        <div class="chart-container">
            <canvas id="chart"></canvas>
        </div>
        </div>
        </div>
    </main>

        <script>
            // Initialize the chart
            var ctx = document.getElementById('chart').getContext('2d');
            var myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: <?php echo json_encode(array_keys($categories)); ?>,
                    datasets: [{
                        data: <?php echo json_encode(array_values($categories)); ?>,
                        backgroundColor: [
                            // Add colors for each category
                            'rgba(255, 99, 132, 0.2)',
                            'rgba(54, 162, 235, 0.2)',
                            'rgba(255, 206, 86, 0.2)',
                            'rgba(75, 192, 192, 0.2)',
                            'rgba(153, 102, 255, 0.2)',
                            'rgba(255, 159, 64, 0.2)'
                        ],
                        borderColor: [
                            'rgba(255, 99, 132, 1)',
                            'rgba(54, 162, 235, 1)',
                            'rgba(255, 206, 86, 1)',
                            'rgba(75, 192, 192, 1)',
                            'rgba(153, 102, 255, 1)',
                            'rgba(255, 159, 64, 1)'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        labels: {
                            fontColor: 'red'
                    }
                }
            }
            });
        </script>
        
</body>
</html>

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
    $counts = [];
    foreach ($products_of_viewing_user as $product) {
        if (!isset($categories[$product['cat_name']])) {
            $categories[$product['cat_name']] = 0;
        }
        $categories[$product['cat_name']]++;
        $categories[$product['cat_id']]++;
    }

    $stmt = $conn->prepare("SELECT * from product_category");
    $stmt->execute();
    $category_results = $stmt->get_result();

    $stmt->close();
    $conn->close();
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
            echo "<div class='flex-container'>";
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
                   //echo "<td><img src='images/" . $product['product_image'] . "' alt='" . $product['product_name'] . "' class='listing-img'></td>";
                    echo "<td><img src='images/" . $product['product_image'] . "' alt='" . $product['product_name'] . "' class='listing-img' style='width: 80px; height: auto;'></td>";
                    echo "<td>&dollar;" . $product['price'] . "</td>";
                    echo "<td>" . $product['cat_name'] . "</td>";
                    echo "<td>";
                    echo "<form action='delete_listing.php' method='post'>
                            <label for='product_id_" . $product['product_id'] . "' class='visually-hidden'>Delete " . $product['product_id'] . "</label>
                            <input type='hidden' id='product_id_" . $product['product_id'] . "' name='product_id' value='" . $product['product_id'] . "'>
                            <button type='submit'>Delete</button>
                        </form>";
                    echo "<button id='editListingBtn' class='btn btn-custom' data-toggle='collapse' data-target='#editListingForm'>Edit Listing (work in progress)</button>";
                    echo  '<div id="editListingForm" class="collapse">
                                <form action="edit_listing.php" method="post" class="card p-3">
                                    <div class="form-group">
                                        <label for="Product Name">Product Name:</label>
                                        <input type="text" class="form-control" id="pname" name="pname" value="'.htmlspecialchars($product['product_name']).'">
                                    </div>
                                    <div class="form-group">
                                        <label for="price">Price (&dollar;):</label>
                                        <input type="text" class="form-control" id="price" name="price" value="'.htmlspecialchars($product['price']).'">
                                    </div>
                                    <div class="form-group">
                                        <label for="cat" class="form-label">Category:</label>
                                        <select class="form-control" id="cat" name="cat">';
                                echo' <option value="'. $product['cat_id']. '">'. $product['cat_name'] .'</option>';
                                        while ($category = $category_results->fetch_assoc()) {
                                            if($category['cat_id'] != $product['cat_id']){
                                                echo '<option value="' . $category['cat_id'] . '">' . $category['cat_name'] . '</option>';
                                            }
                                            
                                        }

                                        $category_results->data_seek(0);
                                echo '</select>

                                    </div>
                                    <button type="submit" class="btn btn-custom">Update Listing</button>
                                </form>
                            </div>';
                    echo "</td>";
                    echo "</tr>";
                }
                echo "</tbody>";
                echo "</table>";
                echo "</div>";
            }
        ?>
        <div class="chart-container" style="position: relative; height:80; width:120">
            <canvas id="chart"></canvas>
        </div>
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
                    maintainAspectRatio: false
                }
            });
        </script>
        </section>  
    </main>
</body>
</html>
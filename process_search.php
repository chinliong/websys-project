<?php
    session_start();
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
    <script src="js/async.js"></script>
</head>
<body>    
    <?php
    include "inc/nav.inc.php";
    ?>

<main class="container">

    <section id="search-results">
    <?php

        $search = $_POST["search"];
        $search = sanitize_input($search);     
        $cat = $_POST["cat"];
        $cat = sanitize_input($cat);

        if ($search ==""){
            echo '<h2>Producing Results for "All Products"</h2>';
        } else{
            echo '<h2>Producing Results for "' . $search . '"</h2>';
        }
        
    ?>
    <
    <div class="row">
    <?php 
    include "db_con.php";

    // Check connection
    if ($conn->connect_error)
    {
    $errorMsg = "Connection failed: " . $conn->connect_error;
    $success = false;
    }
    else
    {
    if ($cat == "all_cats_in_db"){
        $stmt = $conn->prepare("SELECT
                                p.product_id,
                                p.product_name, 
                                u.username AS seller_name, 
                                p.price, 
                                p.product_image, 
                                c.cat_name AS cat_name
                                FROM 
                                    product_table p
                                JOIN 
                                    user_table u ON p.user_id = u.user_id
                                JOIN 
                                    product_category c ON p.cat_id = c.cat_id
                                WHERE p.product_name LIKE ?");
        $search_value = "%" . $search ."%";
        $stmt->bind_param("s", $search_value);
    } else{
        $stmt = $conn->prepare("SELECT
                            p.product_id,
                            p.product_name, 
                            u.username AS seller_name, 
                            p.price, 
                            p.product_image, 
                            c.cat_name AS cat_name
                            FROM 
                                product_table p
                            JOIN 
                                user_table u ON p.user_id = u.user_id
                            JOIN 
                                product_category c ON p.cat_id = c.cat_id
                            WHERE 
                                p.cat_id = ?
                                AND p.product_name LIKE ?");
        $search_value = "%" . $search ."%";
        $stmt->bind_param("si", $search_value, $cat);

        

        //[Front-end] display search results
        $result = $stmt->get_result();
        
        //get values for the chart
        //x-axis is the prices
        //y-axis is the frequencies
        $prices = [];
        while ($row = $result->fetch_assoc()) {
            $prices[] = $row['price'];
        }

        $frequencies = array_count_values($prices);
        ksort($frequencies);

        $prices = array_keys($frequencies);
    }
        if (!$stmt->execute())
        {
        $errorMsg = "Execute failed: (" . $stmt->errno . ") " .
        $stmt->error;
        $success = false;

        }
        while ($row = $result->fetch_assoc()) {
            echo '<article class="col-md-4 product">';
            echo '<a href="product_page.php?id=' . $row["product_id"] . '">';
            echo '<img class="product-image" src="/images/' . $row["product_image"] . '" alt="' . $row["product_name"] . '" class="img-fluid">';          
            echo '<h3>' . $row["product_name"] . '</h3>';
            echo '</a>';
            echo '<p>$' . $row["price"] . '</p>';
            echo '<p>Category: ' . $row["cat_name"] . '</p>';
            echo '<p>Seller: ' . $row["seller_name"] . '</p>';
            if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
                echo '<button type="button" class="btn btn-primary add-to-cart" data-product-id="' . $row["product_id"] . '">Add to Cart</button>';
            }
            echo '</article>';
        }

    $stmt->close();
    }
    $conn->close();
    
    function sanitize_input($data)
    {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    }
    ?>
    </div>
    <div class="chart-container" style="position: relative; height:80; width:120">
            <canvas id="chart"></canvas>
        </div>
    <script>

    // Initialize the chart
    var ctx = document.getElementById('chart').getContext('2d');
    var myChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?php echo json_encode($prices); ?>,
            datasets: [{
                data: <?php echo json_encode($frequencies); ?>,
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
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
    <?php
        include "inc/footer.inc.php";
    ?>
</main>

</body>

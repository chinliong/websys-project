<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ferris Wheel</title>
    <?php 
    include 'inc/head.inc.php';
    include 'inc/header.inc.php';
    ?>
    <script src="js/async.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
   
    <?php
    include "inc/nav.inc.php";
    ?>

<main class="container-sm mt-5">

    <section id="search-results">
    <?php
        $search = $_POST["search"];
        $search = sanitize_input($search);     

        echo '<h2 class="mb-4">';
        if ($search == ""){
            echo '<h2>Producing Results for "All Products"</h2>';
        } else{
            echo '<h2>Producing Results for "' . htmlspecialchars($search) . '"</h2>';
        }
    ?>
    
    <div class="row">
    <?php 
    include "db_con.php";
    $cat = $_POST["cat"];
    $cat = sanitize_input($cat);
    echo "<script>console.log($cat);</script>";
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
                                p.user_id,
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
        echo "<script>console.log($cat);</script>";
        $stmt = $conn->prepare("SELECT
                            p.product_id,
                            p.product_name, 
                            u.username AS seller_name, 
                            p.price,
                            p.user_id,
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
        $stmt->bind_param("is", $cat, $search_value);
    }
        if (!$stmt->execute())
        {
        $errorMsg = "Execute failed: (" . $stmt->errno . ") " .
        $stmt->error;
        $success = false;

        }

        //[Front-end] display search results
        $result = $stmt->get_result();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                if (!isset($_SESSION['userid']) || $_SESSION['userid'] != $row["user_id"]){
                echo '<article class="col-sm-4 product">';
                echo '<div class="card">';
                echo '<a href="product_page.php?id=' . $row["product_id"] . '">';
                echo '<img class="card-img-top" src="/images/' . $row["product_image"] . '" alt="' . $row["product_name"] . '">';    
                echo '<div class="card-body">';
                echo '<h5 class="card-title">' . $row["product_name"] . '</h5>';
                echo '</a>';
                echo '<p class="card-text black-words">$' . $row["price"] . '</p>';
                echo '<p class="card-text black-words">Category: ' . $row["cat_name"] . '</p>';
                echo '<p class="card-text black-words">Seller: ' . $row["seller_name"] . '</p>';
                if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) {
                    echo '<button type="button" class="btn btn-primary add-to-cart" data-product-id="' . $row["product_id"] . '">Add to Cart</button>';
                }
                echo '</div>';
                echo '</div>';
                echo '</article>';

                //collect price data for chart
                $price = $row["price"];
                if (!isset($prices[$price])) {
                    $prices[$price] = 0;
                }
                $prices[$price]++;
            }
            }
     } else {
        echo "<h3 class='warning-messages'>No results found</h3>";
     }
        $stmt->close();
        $jsonPrices = json_encode($prices);
        echo "<script>console.log($jsonPrices);</script>";

        
    //$stmt->close();
    }
    //$conn->close();
    
    function sanitize_input($data)
    {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    }
    ?>
    <canvas id="myChart"></canvas>
    <script>
        var prices = <?php echo $jsonPrices; ?>;
        var ctx = document.getElementById('myChart').getContext('2d');

        // Sort the price labels (keys) in ascending order
        var sortedPrices = Object.keys(prices).sort(function(a, b) {
        return a - b;
        });
        var myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: sortedPrices,
                datasets: [{
                    label: 'Price Distribution',
                    data: Object.values(prices),
                    backgroundColor: 'rgba(255, 99, 132, 0.2)',
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    x: {
                        title:{
                            display: true,
                            text: 'Price'
                        },
                        beginAtZero: true
                    },
                    y: {
                        title:{
                            display: true,
                            text: 'Number of Products sold at this price'
                        },
                        beginAtZero: true
                        
                    }
                }
            }
        });
    </script>
    </div>
    </section>
    <?php
        include "inc/footer.inc.php";
    ?>
</main>

</body>
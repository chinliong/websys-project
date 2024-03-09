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
<body>    
    <?php
    include "inc/nav.inc.php";
    ?>

<main class = "container">

    <section id="search-results">
    <h2>Search Results</h2>
    <div class = "row">
    <?php 
    $search = $_POST["search"];
    $search = sanitize_input($search);
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
        $stmt = $conn->prepare("SELECT * FROM product_table WHERE product_name LIKE ?");
        $search_value = "%" . $search ."%";
        $stmt->bind_param("s", $search_value);

        if (!$stmt->execute())
        {
        $errorMsg = "Execute failed: (" . $stmt->errno . ") " .
        $stmt->error;
        $success = false;

        }
        $result = $stmt->get_result();
        while ($row = $result->fetch_assoc()) {
            echo '<article class="col-md-4">';
            echo '<img src="/images/' . $row["product_image"] . '" alt="' . $row["product_name"] . '" class="img-fluid">';
            echo '<h3>' . $row["product_name"] . '</h3>';
            echo '<p>' . $row["price"] . '</p>';
            echo '<p>' . $row["seller_name"] . '</p>';
            echo '</article>';
        }
    $stmt->close();
    }
    $conn->close();
    }
    function sanitize_input($data)
    {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
    }
    ?>
    </div>
    </section>
</main>
</body>
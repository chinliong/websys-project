<?php
    session_start();
    if ((!($_SESSION['role'] == 'a')) || ($_SESSION['loggedin'] != true)) {
        if (!($_SESSION['role'] == 'admin')){
            header('Location: ../error.php');
        }
    } else{
        include 'db_con.php';

        $sql = "SELECT * FROM user_table";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $user_table_results = $stmt->get_result();

        if($user_table_results){
            $user_table = $user_table_results->fetch_all(MYSQLI_ASSOC);
        } else {
            echo "Error fetching user table";
        }

        $stmt->close();

        $stmt = $conn->prepare("SELECT * FROM product_table");
        $stmt->execute();
        $product_table_results = $stmt->get_result();

        if($product_table_results){
            $product_table = $product_table_results->fetch_all(MYSQLI_ASSOC);
        } else {
            echo "Error fetching product table";
        }

        $stmt->close();

        $conn->close();
    }
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
        include 'inc/head.inc.php';
    ?>
    <title>Admin Console</title>
</head>
<body>

    <?php 
        include 'inc/nav.inc.php';

    ?>
    <section id="admin-console">
        <div class="row">
            <article class="col-sm-12">
                <h2 id="admin-header">Admin Console</h2>
                <p id="welcome-messsage-admin">Welcome to the admin console</p>
            </article>
        </div>
        <article class="col-md-6 col-sm-12">
            <h3>Users</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">User ID</th>
                        <th scope="col">Username</th>
                        <th scope="col">Email</th>
                        <th scope="col">Role</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($user_table as $user){
                            echo "<tr>";
                            echo "<td>".$user['user_id']."</td>";
                            echo "<td>".$user['username']."</td>";
                            echo "<td>".$user['email']."</td>";
                            echo "<td>".$user['user_role']."</td>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </article>
                        
        <article class="col-sm-12">
            <h3>Products</h3>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th scope="col">Product ID</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach($product_table as $product){
                            echo "<tr>";
                            echo "<td>".$product['product_id']."</td>";
                            echo "<td>".$product['product_name']."</td>";
                            echo "<td>".$product['price']."</td>";
                            echo "<form action='delete_listing.php' method='post'>
                            <label for='product_id_" . $product['product_id'] . "' class='visually-hidden'>Delete " . $product['product_id'] . "</label>
                            <input type='hidden' id='product_id_" . $product['product_id'] . "' name='product_id' value='" . $product['product_id'] . "'>
                            <button type='submit'>Delete</button>
                            </form>";
                            echo "</tr>";
                        }
                    ?>
                </tbody>
            </table>
        </article>
    </section>  
</body>
</html>
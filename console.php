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
                        <th scope="col">Created At</th>
                        <th scope="col">Funds</th>
                        <th scope="col">Status</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($user_table as $user): ?>
                    <tr>
                        <td><?= htmlspecialchars($user['user_id']) ?></td>
                        <td><?= htmlspecialchars($user['username']) ?></td>
                        <td><?= htmlspecialchars($user['email']) ?></td>
                        <td><?= htmlspecialchars($user['user_role']) ?></td>
                        <td><?= htmlspecialchars($user['created_at']) ?></td>
                        <td><?= htmlspecialchars($user['funds']) ?></td>
                        <td><?= htmlspecialchars($user['status']) ?></td>
                        <td>
                            <a href="edit_userconsole.php?user_id=<?= $user['user_id'] ?>" class="btn btn-info">Edit</a>
                            <a href="delete_userconsole.php?user_id=<?= $user['user_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
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
                        <th scope="col">Product Image</th>
                        <th scope="col">Price</th>
                        <th scope="col">Category ID</th>
                        <th scope="col">User ID</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach($product_table as $product): ?>
                    <tr>
                        <td><?= htmlspecialchars($product['product_id']) ?></td>
                        <td><?= htmlspecialchars($product['product_name']) ?></td>
                        <td><img src="/images/<?= htmlspecialchars($product['product_image']) ?>" style="width: 50px; height: 50px;"></td>
                        <td><?= htmlspecialchars($product['price']) ?></td>
                        <td><?= htmlspecialchars($product['cat_id']) ?></td>
                        <td><?= htmlspecialchars($product['user_id']) ?></td>
                        <td>
                            <a href="edit_productconsole.php?product_id=<?= $product['product_id'] ?>" class="btn btn-info">Edit</a>
                            <a href="delete_productconsole.php?product_id=<?= $product['product_id'] ?>" class="btn btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </article>
    </section>  
</body>
</html>
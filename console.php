<?php
session_start();
if ((!($_SESSION['role'] == 'a')) || ($_SESSION['loggedin'] != true)) {
    if (!($_SESSION['role'] == 'admin')) {
        header('Location: ../error.php');
    }
} else {
    include 'db_con.php';

    $sql = "SELECT * FROM user_table";
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $user_table_results = $stmt->get_result();

    if ($user_table_results) {
        $user_table = $user_table_results->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "Error fetching user table";
    }

    $stmt->close();

    $query = "SELECT 
            pt.product_id,
            pt.product_name,
            pt.product_image,
            pt.price,
            ut.username,
            pc.cat_name
          FROM 
            product_table pt
          JOIN 
            user_table ut ON pt.user_id = ut.user_id
          JOIN 
            product_category pc ON pt.cat_id = pc.cat_id";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $product_table_results = $stmt->get_result();


    if ($product_table_results) {
        $product_table = $product_table_results->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "Error fetching product table";
    }

    $stmt->close();

    // SQL to fetch transaction history
    $query = "SELECT
    t.transaction_id,
    buyer.username AS buyer_username,
    seller.username AS seller_username,
    pc.cat_name,
    pt.product_name,
    t.price,
    t.created_at
FROM
    transaction_table t
JOIN
    user_table buyer ON t.buyer_id = buyer.user_id
JOIN
    user_table seller ON t.seller_id = seller.user_id
JOIN
    product_table pt ON t.products_id = pt.product_id
JOIN
    product_category pc ON t.category_id = pc.cat_id
";

    $stmt = $conn->prepare($query);
    $stmt->execute();
    $transaction_history_table_result = $stmt->get_result();

    if ($transaction_history_table_result ) {
        $transaction_history_table = $transaction_history_table_result ->fetch_all(MYSQLI_ASSOC);
    } else {
        echo "Error fetching transaction history";
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
    <?php include 'inc/head.inc.php'; ?>
    <script src="js/console.js" defer></script>
    <title>Admin Console</title>
</head>
<body>
    <?php include 'inc/nav.inc.php'; ?>
    <main class="container my-4">
        <h1 class="mb-4 text-center" style="margin-top: 50px">Admin Console</h1>
        
        <!-- Buttons to toggle tables -->
        <div class="text-center mb-3">
            <button onclick="showTable('usersTable')" class="btn admin-btn">Show Users</button>
            <button onclick="showTable('productsTable')" class="btn admin-btn">Show Products</button>
            <button onclick="showTable('transactionsTable')" class="btn admin-btn">Show Transaction History</button>
        </div>

        <div id="usersTable" style="display:none;">
            <!-- Users Table -->
            <h2>Users</h2>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-nowrap">
                                <th scope="col" class="col-1">User ID</th>
                                <th scope="col" class="col-2">Username</th>
                                <th scope="col" class="col-3">Email Address</th>
                                <th scope="col" class="col-1">Role</th>
                                <th scope="col" class="col-2">Created</th>
                                <th scope="col" class="col-1">Funds</th>
                                <th scope="col" class="col-1">Status</th>
                                <th scope="col" class="col-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($user_table as $user) : ?>
                                <tr class="text-nowrap" style="text-align: center;">
                                    <td class="col-1"><?= htmlspecialchars($user['user_id']) ?></td>
                                    <td class="col-2"><?= htmlspecialchars($user['username']) ?></td>
                                    <td class="col-3"><?= htmlspecialchars($user['email']) ?></td>
                                    <td class="col-1"><?= $user['user_role'] == 'a' ? 'Admin' : 'User' ?></td>
                                    <td class="col-2"><?= htmlspecialchars($user['created_at']) ?></td>
                                    <td class="col-1"><?= htmlspecialchars($user['funds']) ?></td>
                                    <td class="col-1"><?= $user['status'] == 1 ? 'Verified' : 'Not Verified' ?></td>
                                    <td class="col-1">
                                        <a href="edit_userconsole.php?user_id=<?= $user['user_id'] ?>" class="btn btn-sm" style="background-color: #0069d9; color: white;">Edit</a>
                                        <a href="delete_userconsole.php?user_id=<?= $user['user_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this user?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </div>
        
        <div id="productsTable" style="display:none;">
            <!-- Products Table -->
            <h2>Products</h2>
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr class="text-nowrap">
                                <th scope="col" class="col-1">Product ID</th>
                                <th scope="col" class="col-2">Product Name</th>
                                <th scope="col" class="col-3">Product Image</th>
                                <th scope="col" class="col-1">Price</th>
                                <th scope="col" class="col-2">Category</th>
                                <th scope="col" class="col-1">User</th>
                                <th scope="col" class="col-1">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($product_table as $product) : ?>
                                <tr class="text-nowrap" style="text-align: center;">
                                    <td class="col-1"><?= htmlspecialchars($product['product_id']) ?></td>
                                    <td class="col-2"><?= htmlspecialchars($product['product_name']) ?></td>
                                    <td class="col-3"><img src="/images/<?= htmlspecialchars($product['product_image']) ?>" class="img-fluid rounded" style="width: 50px; height: 50px;"></td>
                                    <td class="col-1"><?= htmlspecialchars($product['price']) ?></td>
                                    <td class="col-2"><?= htmlspecialchars($product['cat_name']) ?></td>
                                    <td class="col-1"><?= htmlspecialchars($product['username']) ?></td>
                                    <td class="col-1">
                                        <a href="edit_productconsole.php?product_id=<?= $product['product_id'] ?>" class="btn btn-sm " style="background-color: #0069d9; color: white;">Edit</a>
                                        <a href="delete_productconsole.php?product_id=<?= $product['product_id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this product?');">Delete</a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
        </div>

        <div id="transactionsTable" style="display:none;">
            <!-- Transaction History Table -->
            <h2>Transaction History</h2>
            <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr class="text-nowrap">
                        <th scope="col" class="col-1">Transaction ID</th>
                        <th scope="col" class="col-2">Date</th>
                        <th scope="col" class="col-3">Buyer</th>
                        <th scope="col" class="col-1">Seller</th>
                        <th scope="col" class="col-1">Product Name</th>
                        <th scope="col" class="col-1">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transaction_history_table as $row) : ?>
                        <tr class="text-nowrap" style="text-align: center;">
                            <td class="col-1"><?= htmlspecialchars($row['transaction_id']) ?></td>
                            <td class="col-2"><?= htmlspecialchars($row['created_at']) ?></td>
                            <td class="col-3"><?= htmlspecialchars($row['buyer_username']) ?></td>
                            <td class="col-1"><?= htmlspecialchars($row['seller_username']) ?></td>
                            <td class="col-1"><?= htmlspecialchars($row['product_name']) ?></td>
                            <td class="col-1">$<?= htmlspecialchars(number_format($row['price'], 2)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        </div>
        
    </main>
    <?php include "inc/footer.inc.php"; ?>
</body>
</html>

<?php
// Include the initialisation file which should have session_start() and the database connection ($conn)
session_start();
include 'db_con.php';

// Redirect if not logged in
if (!isset($_SESSION['userid'])) {
    header("Location: login.php"); // Redirect to login page if not logged in
    exit;
}

$userId = $_SESSION['userid']; // Get the logged-in user's ID

$query = "
SELECT
    t.transaction_id,
    buyer.username AS buyer_username,
    seller.username AS seller_username,
    pc.cat_name,
    pt.product_name,
    t.price
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
WHERE
    t.buyer_id = ? OR t.seller_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("ii", $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Transaction History</title>
    <?php include 'inc/head.inc.php'; // Include head tags and CSS ?>
</head>
<body>
    <?php
    include "inc/nav.inc.php"; // Include navigation bar
    include "inc/header.inc.php"; // Include page header
    ?>

    <main class="container">
        <h3>Your Transaction History</h3>
        <?php if ($result->num_rows > 0): ?>
            <table class="table">
                <thead>
                    <tr>
                        <th scope="col">Transaction ID</th>
                        <th scope="col">Buyer</th>
                        <th scope="col">Seller</th>
                        <th scope="col">Category</th>
                        <th scope="col">Product Name</th>
                        <th scope="col">Price</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= htmlspecialchars($row["transaction_id"]) ?></td>
                            <td><?= htmlspecialchars($row["buyer_username"]) ?></td>
                            <td><?= htmlspecialchars($row["seller_username"]) ?></td>
                            <td><?= htmlspecialchars($row["cat_name"]) ?></td>
                            <td><?= htmlspecialchars($row["product_name"]) ?></td>
                            <td><?= htmlspecialchars($row["price"]) ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>No transactions found.</p>
        <?php endif; ?>
    </main>

    <?php
    // Close statement and connection if they were successful
    if ($stmt) {
        $stmt->close();
    }
    if ($conn) {
        $conn->close();
    }
    
    include "inc/footer.inc.php"; // Include the footer
    ?>
</body>
</html>

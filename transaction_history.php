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
    CASE WHEN t.buyer_id = ? THEN 'Buyer' ELSE 'Seller' END AS user_role,
    CASE WHEN t.buyer_id = ? THEN 'Deducted from Wallet' ELSE 'Added to Wallet' END AS wallet_effect,
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
WHERE
    t.buyer_id = ? OR t.seller_id = ?
";

$stmt = $conn->prepare($query);
$stmt->bind_param("iiii", $userId, $userId, $userId, $userId);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Transaction History</title>
    <?php include 'inc/head.inc.php'; // Include head tags and CSS ?>
</head>
<body>
    <?php
    include "inc/nav.inc.php"; // Include navigation bar
    include "inc/header.inc.php"; // Include page header
    ?>

<main class="container mt-5">
    <h1 class="text-center mb-4">Your Transaction History</h1>
    <?php if ($result->num_rows > 0): ?>
        <div class="row">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card transaction-card h-100 <?= $row['user_role'] === 'Buyer' ? 'border-danger' : 'border-success'; ?>">
                        <div class="card-header <?= $row['user_role'] === 'Buyer' ? 'bg-danger text-white' : 'bg-success text-white'; ?>">
                            Transaction #<?= htmlspecialchars($row["transaction_id"]) ?>
                        </div>
                        <div class="card-body">
                            <h2 class="card-title"><?= htmlspecialchars($row["product_name"]) ?></h2>
                            <p class="card-text black-words">
                                <strong>Role:</strong> <?= $row['user_role'] === 'Buyer' ? '<i class="fas fa-shopping-cart"></i> Buyer' : '<i class="fas fa-store"></i> Seller'; ?>
                            </p>
                            <p class="card-text black-words">
                                <strong>Price:</strong> <span class="<?= $row['wallet_effect'] === 'Added to Wallet' ? 'text-success' : 'text-danger'; ?>">
                                    $<?= number_format(htmlspecialchars($row["price"]), 2) ?>
                                </span>
                            </p>
                            <p class="card-text black-words"><strong>Category:</strong> <?= htmlspecialchars($row["cat_name"]) ?></p>
                            <p class="card-text black-words">
                                <strong><?= $row['user_role'] === 'Buyer' ? 'Seller: ' : 'Buyer: ' ?></strong> <?= htmlspecialchars($row['user_role'] === 'Buyer' ? $row["seller_username"] : $row["buyer_username"]) ?>
                            </p>
                            <p class="card-text black-words">
                                <strong>Date:</strong> <?= date("F j, Y", strtotime($row["created_at"])) ?>
                            </p>
                        </div>
                        <div class="card-footer text-muted">
                            <?= $row['wallet_effect'] ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    <?php else: ?>
        <p class="text-center">No transactions found.</p>
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

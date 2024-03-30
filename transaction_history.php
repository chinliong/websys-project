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
$stmt->bind_param("iiii", $userId, $userId, $userId, $userId);
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

<main class="container mt-5">
    <h3 class="text-center mb-4">Your Transaction History</h3>
    <?php if ($result->num_rows > 0): ?>
        <div class="row">
            <?php while($row = $result->fetch_assoc()): ?>
                <div class="col-md-4 mb-4">
                    <div class="card transaction-card <?= $row['user_role'] === 'Buyer' ? 'border-danger' : 'border-success'; ?>">
                        <div class="card-body">
                            <h5 class="card-title"><?= htmlspecialchars($row["product_name"]) ?></h5>
                            <p class="card-text"><strong>Role:</strong> <?= $row['user_role'] === 'Buyer' ? '<i class="fas fa-shopping-cart"></i>' : '<i class="fas fa-cash-register"></i>'; ?> <?= htmlspecialchars($row["user_role"]) ?></p>
                            <p class="card-text"><?= $row['wallet_effect'] === 'Added to Wallet' ? '<span class="text-success">' : '<span class="text-danger">'; ?><?= htmlspecialchars($row["wallet_effect"]) ?></span></p>
                            <p class="card-text"><strong>Price:</strong> $<?= number_format(htmlspecialchars($row["price"]), 2) ?></p>
                            <p class="card-text"><strong>Category:</strong> <?= htmlspecialchars($row["cat_name"]) ?></p>
                            <button class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#transactionModal<?= $row['transaction_id']; ?>">Details</button>
                        </div>
                    </div>
                </div>

                <!-- Modal for Transaction Details -->
                <div class="modal fade" id="transactionModal<?= $row['transaction_id']; ?>" tabindex="-1" aria-labelledby="transactionModalLabel" aria-hidden="true">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="transactionModalLabel">Transaction Details</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Populate this with detailed transaction info -->
                                <p>Transaction ID: <?= $row['transaction_id']; ?></p>
                                <!-- Add more detailed information here -->
                            </div>
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

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Check Out</title>
    <?php
    include 'inc/head.inc.php';
    ?>
</head>

<body>
    <?php
    include "inc/nav.inc.php";
    include "inc/header.inc.php";
    ?>
    <main class="container">
        <h2>Checkout Page</h2>
        <?php
        $config = parse_ini_file('/var/www/private/db-config.ini');
        if (!$config) {
            $errorMsg = "Failed to read database config file.";
            $success = false;
        } else {
            $conn = new mysqli(
                $config['servername'],
                $config['username'],
                $config['password'],
                $config['dbname']
            );

            if ($conn->connect_error) {
                $errorMsg = "Connection failed: " . $conn->connect_error;
                $success = false;
            } else {
                $user_id_cart_name = $_SESSION['userid'];
                $stmt = $conn->prepare("SELECT
                pt.product_name,
                ut.username AS seller_name,
                pt.product_image,
                pt.price,
                pc.cat_name AS cat_name
            FROM
                cart_table c
            JOIN
                product_table pt ON c.product_id = pt.product_id
            JOIN
                user_table ut ON pt.user_id = ut.user_id
            JOIN
                product_category pc ON pt.cat_id = pc.cat_id
            WHERE
                c.user_id = ?");
                $stmt->bind_param("i", $user_id_cart_name);

                if (!$stmt->execute()) {
                    $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                    $success = false;
                } else {
                    $result = $stmt->get_result();
                    ?>
                    <!-- Table for Mobile Phones -->
                    <div class="table-responsive d-block d-sm-none">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th scope="col" style="font-size: 10px;">Product Name</th>
                                    <th scope="col" style="font-size: 10px;">Price</th>
                                    <th scope="col" style="font-size: 10px;">Category</th>
                                    <th scope="col" style="font-size: 10px;">Seller Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $subtotal = 0;
                                $item_count = 0;
                                while ($row = $result->fetch_assoc()) {
                                    $subtotal += $row["price"]; // Add the price of each product to the subtotal
                                    $item_count++;
                                    echo '<tr>';
                                    echo '<td class="listing-img-smallercheckout"><img src="/images/' . $row["product_image"] . '" alt="alt' . $row["product_name"] . '" style="width: 30px; height: 30px;"> <span style="font-size: 10px; display: block;">' . $row["product_name"] . '</span></td>';
                                    echo '<td style="font-size: 10px;">$' . $row["price"] . '</td>';
                                    echo '<td style="font-size: 10px;">' . $row["cat_name"] . '</td>';
                                    echo '<td style="font-size: 10px;">' . $row["seller_name"] . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    // Reset result set pointer
                    $result->data_seek(0);
                    ?>
                    <!-- Normal Table for Larger Screens -->
                    <div class="table-responsive d-none d-sm-block">
                        <table class="table align-middle">
                            <thead>
                                <tr>
                                    <th scope="col">Product Name</th>
                                    <th scope="col">Price</th>
                                    <th scope="col">Category</th>
                                    <th scope="col">Seller Name</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $subtotal = 0;
                                $item_count = 0;
                                while ($row = $result->fetch_assoc()) {
                                    $subtotal += $row["price"]; // Add the price of each product to the subtotal
                                    $item_count++;
                                    echo '<tr>';
                                    echo '<td><img src="/images/' . $row["product_image"] . '" alt="alt' . $row["product_name"] . '" style="width: 50px; height: 50px;"> ' . $row["product_name"] . '</td>';
                                    echo '<td>$' . $row["price"] . '</td>';
                                    echo '<td>' . $row["cat_name"] . '</td>';
                                    echo '<td>' . $row["seller_name"] . '</td>';
                                    echo '</tr>';
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <!-- Voucher and Shipping Options -->
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="voucherCode" class="form-label">Voucher Code</label>
                            <input type="text" class="form-control" id="voucherCode" placeholder="Enter voucher code">
                        </div>
                        <div class="col-md-6">
                            <label for="shippingOption" class="form-label">Shipping Option</label>
                            <select class="form-select" id="shippingOption">
                                <option>Doorstep Delivery</option>
                                <option>Self-Collection</option>
                            </select>
                        </div>
                    </div>

                    <!-- <hr> Add a horizontal line -->
                    <div style="text-align: right;">
                        <div style="display: inline-block;">
                            <p>Subtotal (<?php echo $item_count; ?> items): $<?php echo $subtotal; ?></p> <!-- Display the subtotal -->
                            <form action="payment.php" method="post">
                                <input type="hidden" name="amount" value="<?php echo $subtotal; ?>">
                                <button type="button" class="btn btn-primary pay-with-funds-btn">
                                    Pay with Funds
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Modal -->
                    <div class="modal fade" id="paymentStatusModal" tabindex="-1" aria-labelledby="paymentStatusModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="paymentStatusModalLabel">Payment Status</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                                    </button>
                                </div>
                                <div class="modal-body" id="paymentStatusMessage">
                                    <!-- Payment status message will be displayed here -->
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
        <?php
                    $stmt->close();
                }
                $conn->close();
            }
        }
        ?>
    </main>
    <?php
    include "inc/footer.inc.php";
    ?>
<script>
    document.querySelector('.pay-with-funds-btn').addEventListener('click', function () {
        // Make an AJAX request to payment.php
        $.ajax({
            url: 'payment.php',
            type: 'POST',
            data: {
                // Include any data you need to send to payment.php
                amount: <?php echo $subtotal; ?>,
            },
            success: function (response) {
                // On success, display the payment status in the modal
                $('#paymentStatusMessage').html(response);
                $('#paymentStatusModal').modal('show');
            },
            error: function () {
                // Handle error
                $('#paymentStatusMessage').html('Payment failed. Please try again.');
                $('#paymentStatusModal').modal('show');
            }
        });
    });
</script>

<script>
    $('#paymentStatusModal').on('hidden.bs.modal', function () {
        // This code will run after the modal has been hidden
        window.location.href = 'index.php'; // Redirect to index.php
    });
</script>
</body>
</html>

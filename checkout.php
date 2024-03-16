<?php
include 'inc/head.init.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Check Out</title>
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
        include "inc/header.inc.php";
        ?>
        <main class="container">
            <h3>Checkout Page</h3>
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
                    $stmt = $conn->prepare("SELECT cart_table.*, product_table.* FROM cart_table 
                    RIGHT JOIN product_table ON product_table.product_id = cart_table.product_id 
                    WHERE user_id = ?;");
                    $stmt->bind_param("i", $user_id_cart_name);

                    if (!$stmt->execute()) {
                        $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
                        $success = false;
                        } else {
                            $result = $stmt->get_result();
                            ?>
                            <div class="table-responsive">
                                <table class="table">
                                    <thead>
                                        <tr>
                                            <th scope="col">Product Name</th>
                                            <th scope="col">Price</th>
                                            <th scope="col">Category</th>
                                            <th scope="col">Seller Name</th>
                                            <th scope="col">Quantity</th>
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
                                            echo '<td><img src="/images/' . $row["product_image"] . '" style="width: 50px; height: 50px;"> ' . $row["product_name"] . '</td>'; // Display the product image beside the name
                                            echo '<td>$' . $row["price"] . '</td>';
                                            echo '<td>' . $row["cat_id"] . '</td>';
                                            echo '<td>' . $row["seller_name"] . '</td>';
                                            echo '<td>1</td>'; // Replace this with actual quantity
                                            echo '</tr>';
                                            }
                                            ?>
                                            </tbody>
                                            </table>
                                            </div>
                                            <h4>Voucher Code</h4>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="voucherCode">Voucher Code</label>
                                                        <input type="text" class="form-control" id="voucherCode" placeholder="Enter voucher code">
                                                    </div>
                                                </div>
                                            </div>
                                            <hr> <!-- Add a horizontal line -->
                                            <!-- Shipping Option Section -->
                                            <div class="form-group">
                                                <label for="shippingOption">Shipping Option</label>
                                                <select class="form-control" id="shippingOption">
                                                    <option>Doorstep Delivery</option>
                                                    <option>Self-Collection</option>
                                                </select>
                                            </div>

                                            <hr> <!-- Add a horizontal line -->
                                            <h4>Payment Method</h4>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="paymentMethod" id="cashondelivery" value="cashondelivery">
                                                <label class="form-check-label" for="cashondelivery">
                                                    Cash On Delivery
                                                </label>
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="paymentMethod" id="payLah" value="payLah">
                                                <label class="form-check-label" for="payLah">
                                                    PayLah
                                                </label>
                                            </div>
                                            <div class="form-group" id="payLahPhone" style="display: none;">
                                                <label for="phoneNumber">Phone Number</label>
                                                <input type="text" class="form-control" id="phoneNumber" placeholder="Enter your phone number">
                                            </div>
                                            <div class="form-check">
                                                <input class="form-check-input" type="radio" name="paymentMethod" id="creditDebitCard" value="creditDebitCard" data-toggle="modal" data-target="#cardDetailsModal">
                                                <label class="form-check-label" for="creditDebitCard">
                                                    Credit Card/Debit Card
                                                </label>
                                            </div>

                                            <!-- Modal -->
                                            <div class="modal fade" id="cardDetailsModal" tabindex="-1" role="dialog" aria-labelledby="cardDetailsModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="cardDetailsModalLabel">Enter Card Details</h5>
                                                            <button type="button" class="close" id="closeModal" data-dismiss="modal" aria-label="Close">
                                                                <span aria-hidden="true">&times;</span>
                                                            </button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <form>
                                                                <h5>Card Details</h5>
                                                                <div class="form-group">
                                                                    <label for="cardNumber">Card Number</label>
                                                                    <input type="text" class="form-control" id="cardNumber" placeholder="1234 5678 9012 3456">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="cardExpiry">Expiry Date (MM/YY)</label>
                                                                    <input type="text" class="form-control" id="cardExpiry" placeholder="MM/YY">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="cardCVV">CVV</label>
                                                                    <input type="text" class="form-control" id="cardCVV" placeholder="123">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="cardName">Name on Card</label>
                                                                    <input type="text" class="form-control" id="cardName" placeholder="John Doe">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="address">Address</label>
                                                                    <input type="text" class="form-control" id="address" placeholder="123 Main St">
                                                                </div>
                                                                <div class="form-group">
                                                                    <label for="postalCode">Postal Code</label>
                                                                    <input type="text" class="form-control" id="postalCode" placeholder="123456">
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="submit" class="btn btn-primary">Submit</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <script>
                                                // Listen for change event on the radio button
                                                document.getElementById('creditDebitCard').addEventListener('change', function() {
                                                    if (this.checked) {
                                                        // If the "Credit Card/Debit Card" option is selected, show the modal
                                                        $('#cardDetailsModal').modal('show');
                                                    }
                                                });
                                                document.getElementById('closeModal').addEventListener('click', function() {
                                                    $('#cardDetailsModal').modal('hide');
                                                });
                                                document.querySelectorAll('input[name="paymentMethod"]').forEach(function(radio) {
                                                    radio.addEventListener('change', function() {
                                                        // If the "PayLah" option is selected, show the phone number input
                                                        // Otherwise, hide it
                                                        document.getElementById('payLahPhone').style.display = (this.value === 'payLah') ? 'block' : 'none';
                                                    });
                                                });
                                            </script>
                                            <hr> <!-- Add a horizontal line -->
                                            <div style="text-align: right;">
                                                <div style="display: inline-block;">
                                                    <p>Subtotal (<?php echo $item_count; ?> items): $<?php echo $subtotal; ?></p> <!-- Display the subtotal -->
                                                    <a href="checkout.php" class="btn btn-primary">Place Order</a>
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
                                            </body>
                                            </html>

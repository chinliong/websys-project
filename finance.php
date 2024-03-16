<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Profile</title>
    <?php include 'inc/head.inc.php'; ?>
    <script src="https://www.paypal.com/sdk/js?client-id=AYEJbRmndALsBis5z1i7r-e2ArjbQIFwRqHlfvsH9l3xg0w_T1xSsDHt9Mp033tMR0ZSV8FgezaaK4ir&currency=USD"></script>
</head>

<body>
    <?php include 'inc/nav.inc.php'; ?>
    <?php include 'inc/header.inc.php'; ?>

    <main class="container">
        <section id="User Profile">
            <!-- Success and Error Messages -->
            <?php
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
                $user_id = $_SESSION['userid'];
                // Prepare the statement:
                $stmt = $conn->prepare("SELECT funds FROM user_table WHERE user_id = ?");
                // Bind & execute the query statement:
                $stmt->bind_param("i", $user_id);
                $stmt->execute();
                $result = $stmt->get_result();
                if ($result->num_rows > 0) {
                    // Fetch the first row from the result
                    $row = $result->fetch_assoc();
                
                    // Get the funds from the row
                    $funds = $row['funds'];
                } else {
                    echo 'No user found with the provided ID.';
                }
                $stmt->close();
                }
                $conn->close();
                }
            ?>

            <p>Funds: <?php echo $funds; ?></p>
                <label for="deposit">Deposit Amount:</label>
                <input type="text" id="deposit" name="deposit" min="0" required>
                <div id="paypal-button-container"></div> <!-- PayPal button will be rendered here -->
            
        </section>
        <?php include 'inc/footer.inc.php'; ?>
    </main>
    <script>

    // Paypal wants people to use strings. they hate ints and floats
    var depositInput = document.querySelector('#deposit');
    var deposit = depositInput.value.toString();

    depositInput.addEventListener('change', function() {
        deposit = this.value.toString();
    });

    paypal.Buttons({
        createOrder: function(data, actions) {
            // This function sets up the details of the transaction, including the amount and line item details.
            return actions.order.create({
                purchase_units: [{
                    amount: {
                        value: deposit 
                    }
                }]
            });
        },
        onApprove: function(data, actions) {
            // This function captures the funds from the transaction.
            return actions.order.capture().then(function(details) {
                // This function shows a transaction success message to your buyer.
                $.ajax({
                    type: "POST",
                    url: "deposit.php",
                    data: { deposit: depositInput.value },
                    success: function(response) {
                        // You can handle the server response here
                        console.log(response);
                    }
                });
            });
        }
    }).render('#paypal-button-container'); // This function displays Smart Payment Buttons on your web page.
</script>
</body>

</html>
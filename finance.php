<?php
session_start();
if(!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true){
    header("location: login.php");
    exit;
}
ini_set('display_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>User Profile</title>
    <?php include 'inc/head.inc.php'; ?>
    <script src="https://www.paypal.com/sdk/js?client-id=AYEJbRmndALsBis5z1i7r-e2ArjbQIFwRqHlfvsH9l3xg0w_T1xSsDHt9Mp033tMR0ZSV8FgezaaK4ir&currency=SGD"></script>
    <script src="js/paypal.js" defer></script>
</head>

<body>
    <?php include 'inc/nav.inc.php'; 
    ?>
    <?php include 'inc/header.inc.php'; ?>

    <main class="container">
        
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
        <section id="wallet">
            <div class="row">
                <article id="balance" class="col-sm-12">
                    <h3 id="current-balance"><i class="fas fa-wallet"></i> Current Wallet Balance: &dollar;<?php echo $funds; ?></h3>
                    </article>
                <article id="Deposit" class="col-sm-12">
                    <h2>Deposit Funds Here</h2>
                        <label for="deposit">Deposit Amount:</label>
                        <input type="text" id="deposit" name="deposit" min="0" placeholder="S&dollar; Deposit">
                        <div id="paypal-button-container"></div> <!-- PayPal button will be rendered here -->
                </article>
            </div>
        </section>

        <?php include 'inc/footer.inc.php'; ?>
    </main>
</body>

</html>
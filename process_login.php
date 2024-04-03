<?php
    
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ferris Wheel</title>
    <?php 
    include 'inc/head.inc.php';
    ?>
</head>
<body>
    <main>
        <?php
        include "inc/nav.inc.php";
        ?>
        <?php
        $uname = $errorMsg = "";
        $success = true;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            // reCAPTCHA validation
            if (isset($_POST['g-recaptcha-response'])) {
                $captcha = $_POST['g-recaptcha-response'];
                $secretKey = "6LfQNJ8pAAAAABQjhymhCcvEdy-tTgf0INPDf-ys";
                $ip = $_SERVER['REMOTE_ADDR'];
                $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secretKey&response=$captcha&remoteip=$ip");
                $responseKeys = json_decode($response, true);
                if (intval($responseKeys["success"]) !== 1) {
                    $errorMsg .= "CAPTCHA validation failed.<br>";
                    $success = false;
                }
            }
            if (empty($_POST["email"])) {
                $errorMsg .= "Email is required.<br>";
                $success = false;
            } else {
                $uname = sanitize_input($_POST["email"]);
                if (!filter_var($uname, FILTER_VALIDATE_EMAIL)) {
                    $errorMsg .= "Invalid email format.<br>";
                    $success = false;
                }
            }

            if (empty($_POST["pwd"])) {
                $errorMsg .= "Password is required.<br>";
                $success = false;
            } else {
                $password = $_POST["pwd"];
            }

            if ($success) {
                $pwd_hashed = password_hash($password, PASSWORD_DEFAULT);
                authenticateUser();
            }
        }

        if ($success) {
            // Redirect to homepage on successful login with success message
            $_SESSION['successMsg'] = "Login successful! Welcome back, " . htmlspecialchars($uname) . ".";
            header("Location: index.php");
            exit();
        } else {
            // Redirect back to login on failure with error message
            $_SESSION['errorMsg'] = $errorMsg;
            header("Location: login.php");
            exit();
        }


        /*
        * Helper function to authenticate the login.
        */
        function authenticateUser()
        {
            global $uname, $pwd_hashed, $errorMsg, $success;
            // Create database connection.
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
                // Check connection
                if ($conn->connect_error) {
                    $errorMsg = "Connection failed: " . $conn->connect_error;
                    $success = false;
                } else {
                    // Prepare the statement:
                    $stmt = $conn->prepare("SELECT * FROM user_table WHERE email=?");
                    // Bind & execute the query statement:
                    $stmt->bind_param("s", $uname);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    if ($result->num_rows > 0) {
                        // Note that email field is unique, so should only have
                        // one row in the result set.
                        $row = $result->fetch_assoc();
                        $pwd_hashed = $row["password"];
                        // Check if the password matches:
                        if (!password_verify($_POST["pwd"], $pwd_hashed)) {
                            // Don't be too specific with the error message - hackers don't
                            // need to know which one they got right or wrong. :)
                            $errorMsg = "Email not found or password doesn't match...";
                            $success = false;
                        } 
                        else if($row["status"] == 0){
                            $errorMsg = "Please verify your account";
                            $success = false;
                        }
                        else {   
                            session_start();
                            $_SESSION['loggedin'] = true;
                            $_SESSION['userid'] = $row["user_id"];
                            $_SESSION['role'] = $row["user_role"];
                        }
                    } else {
                        $errorMsg = "Email not found or password doesn't match...";
                        $success = false;
                    }
                    $stmt->close();
                }
                $conn->close();
            }
        }

        function sanitize_input($data)
        {
            $data = trim($data);
            $data = stripslashes($data);
            $data = htmlspecialchars($data);
            return $data;
        }
        ?>
        <?php
        include "inc/footer.inc.php";
        ?>
    </main>
</body>
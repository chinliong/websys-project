<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ferris Wheel</title>
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
    <main>
        <?php
        include "inc/nav.inc.php";
        ?>
        <?php
        $uname = $errorMsg = "";
        $success = true;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
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
            echo '<script>
            if (!window.location.hash) {
                window.location = window.location + "#loaded";
                window.location.reload();
            }
            </script>';
            echo '<section id="success-section">';
            echo '<article">';
            echo '<form action="index.php" method="post">';
            echo "<h4 id='login-welcome-banner'>Login successful!</h4>";
            echo "<p id='login-welcome-message-with-name'>Welcome back, " . $uname . "</p>";
            echo '<button id="return-home-button" type="submit" class="btn btn-success">Return to Home</button>';
            echo '</article>';
            echo '</section>';
        } else {
            echo '<section id="error-section">';
            echo '<article">';
            echo '<form action="login.php" method="post">';
            echo "<h3 id='error-h3'>Oops!</h3>";
            echo "<h4 id='error-h4'>Here's what went wrong: </h4>";
            echo "<p id='error-p'>" . $errorMsg . "</p>";
            echo '<button id="login-return-button" type="submit" class="btn btn-warning">Return to Login</button>';
            echo '</article>';
            echo '</section>';
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
                        } else {
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
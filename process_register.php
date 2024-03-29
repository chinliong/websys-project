<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Ferris wheel</title>
    <?php include 'inc/head.inc.php'; ?>
</head>
<body>
<main>
    <?php
        include "inc/nav.inc.php";
        $email = $errorMsg = "";
        $success = true;

        if ($_SERVER["REQUEST_METHOD"] === "POST") {
            if (empty($_POST["uname"])) {
                $errorMsg .= "Username is required.<br>";
                $success = false;
            } else {
                $uname = sanitize_input($_POST["uname"]);
            }

            if (empty($_POST["email"])) {
                $errorMsg .= "Email is required.<br>";
                $success = false;
            } else {
                $email = sanitize_input($_POST["email"]);
                if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
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

            if (empty($_POST["pwd_confirm"])) {
                $errorMsg .= "Confirm password is required.<br>";
                $success = false;
            } else {
                $confirmPassword = $_POST["pwd_confirm"];
            }

            if ($success) {
                // Additional check to make sure password and confirm password match.
                if ($password !== $confirmPassword) {
                    $errorMsg .= "Password and confirm password do not match.<br>";
                    $success = false;
                } else {
                    // Hash the password using password_hash()
                    $pwd_hashed = password_hash($password, PASSWORD_DEFAULT);
                    // Call the function to attempt to save the member to the database.
                    $success = saveMemberToDB($uname, $email, $pwd_hashed, $errorMsg);
                }
            }


        }
    if ($success) {
        session_start();
        // Registration success

        include 'db_con.php';

        $stmt = $conn->prepare("SELECT user_id, user_role, username FROM user_table WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $user_id_result_set = $stmt->get_result();
        $user_result_row = $user_id_result_set->fetch_assoc();
        $stmt->close();
        $conn->close();

        session_start();
                echo "<h4>Registration successful!</h4>";
                echo "<p>Email: " . $email . "</p>";
                // Registration success
                $otp = rand(000000,999999); //generate random 6 digit numbers
                $_SESSION['otp'] = $otp;
                $_SESSION['email'] = $email; // Set session variables
                include 'otp_register.php';
                echo '<script>
                    alert("Register Successfully, OTP sent to ' . $email . '");
                    window.location.replace("otp_verify.php");
                  </script>'; // sends user to verification page

        // Set session variables
        $_SESSION['userid'] = $user_result_row['user_id']; 
        $_SESSION['role'] = $user_result_row["user_role"];
        // $_SESSION['loggedin'] = true; // Important: Set logged in status

        echo '<section id="success-section">';
        echo '<article">';
        // echo '<form action="index.php" method="post">';
        echo "<h4 id='login-welcome-banner'>Registration successful&#44;</h4>";
        echo "<p id='login-welcome-message-with-name'> Welcome to the Family {$user_result_row['username']}&#33;</p>";
        echo '<a id="return-home-button" href="index.php" class="btn btn-success">Return to Home</a>';
        echo '</article>';
        echo '</section>';

    } else {
        echo "<h3>Oops&#33;</h3>";
        echo "<h4>The following input errors were detected&#58;</h4>";
        echo "<p>" . $errorMsg . "</p>";
        echo '<button onclick="history.go(-1);return true;">Return to Sign Up</button>';
    }


    function saveMemberToDB($uname, $email, $pwd_hashed, &$errorMsg) {
        $config = parse_ini_file('/var/www/private/db-config.ini');
        if (!$config) {
            $errorMsg = "Failed to read database config file.";
            return false;
        }

        $conn = new mysqli($config['servername'], $config['username'], $config['password'], $config['dbname']);
        if ($conn->connect_error) {
            $errorMsg = "Connection failed: " . $conn->connect_error;
            return false;
        }
        // Check if username or email already exists
        if ($stmt = $conn->prepare("SELECT * FROM user_table WHERE username=? OR email=?")) {
            $stmt->bind_param("ss", $uname, $email);
            $stmt->execute();
            $result = $stmt->get_result();
            if ($result->num_rows > 0) {
                $row = $result->fetch_assoc();
                if ($row['username'] == $uname) {
                    $errorMsg .= "Username already exists.<br>";
                }
                if ($row['email'] == $email) {
                    $errorMsg .= "Email already exists.<br>";
                }
                $stmt->close();
                $conn->close();
                return false;
            }
            $stmt->close();
        }
        $sql = "INSERT INTO user_table (username, email, user_role, password, funds, status) VALUES (?, ?, 'u', ?, 0.00, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sss", $uname, $email, $pwd_hashed);
        if (!$stmt->execute()) {
            $errorMsg = "Execute failed: (" . $stmt->errno . ") " . $stmt->error;
            $stmt->close();
            $conn->close();
            return false;
        }
        
        $stmt->close();
        $conn->close();
        return true;
    }

    function sanitize_input($data) {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }
    ?>
    <?php include "inc/footer.inc.php"; ?>
</main>
</body>
</html>

<?php session_start() ?>
<!doctype html>
<html lang="en">
<head>
    <title>Shoppe Haven Verification</title>
    <?php
    include 'inc/head.inc.php';
    ?>
</head>
<body>
<?php
    include 'inc/header.inc.php';
    include 'inc/nav.inc.php';
?>
<!-- <nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="#">Verification Account</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav> -->

<main class="otp-verification-container">
    <div class="otp-verification-card">
        <h1 class="verify">Verify Account</h1>
        <form action="#" method="POST" class="otp-verification-form">
            <div class="otp-verification-input-group">
                <label for="otp">OTP Code</label>
                <input type="text" id="otp" name="otp_code" required>
            </div>
            <div class="otp-verification-input-group">
                <input type="submit" value="Submit" name="verify">
            </div>
        </form>
    </div>
    <?php include 'inc/footer.inc.php'; ?>
</main>

<?php 
    if(isset($_POST["verify"])){
        $otp = $_SESSION['otp'];
        $email = $_SESSION['email'];
        $otp_code = $_POST['otp_code'];  
        if($otp != $otp_code){
            ?>
           <script>
               alert("Invalid OTP code");   
           </script>
           <?php
        }else{
            include 'db_con.php';
            mysqli_query($conn, "UPDATE user_table SET status = '1' WHERE email = '$email'");
            ?>
             <script>
                 alert("Verfication done, you may sign in now");
                   window.location.replace("index.php");
             </script>
             <?php
        }

    }

?>
</body>
</html>



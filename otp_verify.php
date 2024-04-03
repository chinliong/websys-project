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
    include 'inc/nav.inc.php';
    include 'db_con.php';
?>
<!-- <nav class="navbar navbar-expand-lg navbar-light navbar-laravel">
    <div class="container">
        <a class="navbar-brand" href="#">Verification Account</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
    </div>
</nav> -->

<main class="login-form">
    <div class="cotainer">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Verification Account</div>
                        <div class="card-body">
                            <form action="#" method="POST">
                                <div class="form-group row">
                                    <label for="otp" class="col-md-4 col-form-label text-md-right">OTP Code</label>
                                    <div class="col-md-6">
                                        <input type="text" id="otp" class="form-control" name="otp_code" required autofocus>
                                    </div>
                                </div>

                                <div class="col-md-6 offset-md-4">
                                    <input type="submit" value="Verify" name="verify">
                                </div>
                            </form>
                        </div>    
                    </div>
                </div>
            </div>
        </div>
    <?php include 'inc/footer.inc.php'?>

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



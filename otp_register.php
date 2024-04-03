<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';

\Dotenv\Dotenv::createImmutable(__DIR__)->load();
// $dotenv->load();

$mail = new PHPMailer(true); // Create a new PHPMailer instance
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = $_ENV['SMTP_Host'];
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_Username'];
    $mail->Password = $_ENV['SMTP_Password'];
    $mail->SMTPSecure = $_ENV['SMTP_Secure'];
    $mail->Port = $_ENV['SMTP_Port'];

    $userEmail = $_SESSION['email'] ?? 'default_email@example.com';
    $userOtp = $_SESSION['otp'] ?? '000000';

    $mail->setFrom($_ENV['SMTP_Username'], 'OTP Verification'); //set from email and message
    $mail->addAddress($_POST["email"]); // set reply address to user's register address

    $mail->isHTML(true);
    $name = htmlspecialchars($_POST['uname']);
    $mail->Subject="Your verification code";
    $mail->Body = "<p>Dear {$name}, </p><h3>Your verification code is $otp</h3>";

    $mail->send();

} catch(Exception $e){
    echo 'Mailer Error'. $mail->ErrorInfo;
}catch(\Exception){
    echo 'Error: ' . $e->getMessage();
}



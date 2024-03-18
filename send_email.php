<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Adjust the path as needed

$mail = new PHPMailer(true); // Create a new PHPMailer instance

try {
    // Server settings
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'LittleHavenShopee@gmail.com'; // SMTP username
    $mail->Password = 'qiab iuws qfjt fveh'; // SMTP password
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;

    // Sender and recipient settings
    $mail->setFrom('yourgmail@gmail.com', 'Your Name');
    $mail->addReplyTo($_POST['email'], $_POST['name']);
    $mail->addAddress('yourgmail@gmail.com', 'Your Name'); // Where you want the contact messages to be sent

    // Message content
    $mail->isHTML(true);
    $mail->Subject = 'New contact from ' . $_POST['name'];
    $mail->Body = nl2br(e('Name: ' . $_POST['name'] . "\n" .
                          'Email: ' . $_POST['email'] . "\n" .
                          'Message: ' . $_POST['message']));

    // Send the message and check for success
    if ($mail->send()) {
        echo 'Message has been sent';
    } else {
        echo 'Message could not be sent.';
    }
} catch (Exception $e) {
    echo 'Mailer Error: ' . $mail->ErrorInfo;
}

// A simple helper function to safely encode user input
function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}
?>

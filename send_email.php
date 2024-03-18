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

if (isset($_POST['name'], $_POST['email'], $_POST['message'])) {
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_Host'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_Username'];
        $mail->Password = $_ENV['SMTP_Password'];
        $mail->SMTPSecure = $_ENV['SMTP_Secure'];
        $mail->Port = $_ENV['SMTP_Port'];

        // Sender and recipient settings
        $mail->setFrom($_POST['email'], $_POST['name']);
        $mail->addReplyTo($_POST['email'], $_POST['name']);
        $mail->addAddress($_ENV['SMTP_Username'], 'Admin'); // Where you want the contact messages to be sent

        // Message content
        $mail->isHTML(true);
        $mail->Subject = ' Query from ' . $_POST['name'];
        $mail->Body = nl2br(e('Name: ' . $_POST['name'] . "\n" .
                            'Email: ' . $_POST['email'] . "\n" .
                            'Message: ' . $_POST['message']));
        $mail->send();

        echo json_encode(['success' => true, 'message' => 'Email sent successfully']);
        }catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
        }
    }
    

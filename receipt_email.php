<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php';
\Dotenv\Dotenv::createImmutable(__DIR__)->load();

$mail = new PHPMailer(true);

function e($string) {
    return htmlspecialchars($string, ENT_QUOTES, 'UTF-8');
}

// Assuming $transactionDetails is passed to this script after a successful transaction
if (isset($transactionDetails)) {
    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host = $_ENV['SMTP_Host'];
        $mail->SMTPAuth = true;
        $mail->Username = $_ENV['SMTP_Username'];
        $mail->Password = $_ENV['SMTP_Password'];
        $mail->SMTPSecure = $_ENV['SMTP_Secure'];
        $mail->Port = $_ENV['SMTP_Port'];

        // Sender and recipient
        $mail->setFrom($_ENV['SMTP_Username'], 'ShopeeHaven');
        $mail->addAddress($transactionDetails['buyer_email'], $transactionDetails['buyer_name']);

        $mail->isHTML(true);
        $mail->Subject = 'Your transaction details';
        
        $mailBody = "<h1>Thank you for your purchase " . ($transactionDetails['buyer_name']) ."! </h1>";
        $mailBody .= "<table>";
        $mailBody .= "<tr><th>Product</th><th>Price</th></tr>";
        foreach ($transactionDetails['products'] as $productName) {
            $mailBody .= "<tr><td>" . ($productName) . "</td><td>$" . (number_format($product['price'], 2)) . "</td></tr>";
        }
        $mailBody .= "</table>";
        // $mailBody .= "<h2>Seller:" . e($transactionDetails['seller_name']) . "</h2>";
        $mailBody .= "<p>Total: $" . (number_format($transactionDetails['total'], 2)) . "</p>";
        
        $mail->Body = $mailBody;
        $mail->send();

        // echo json_encode(['success' => true, 'message' => 'Receipt email sent successfully']);
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => 'Mailer Error: ' . $mail->ErrorInfo]);
    }
}

    

<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'inc/head.inc.php';?>
    <title>Contact Form</title>
</head>
<body>
    <?php include 'inc/nav.inc.php'?>

    <form method="POST" action="send_email.php" id="contact-form">
        <h2>Contact us</h2>
        <p><label>First Name:</label> <input name="name" type="text" required /></p>
        <p><label>Email Address:</label> <input name="email" type="email" required /></p>
        <p><label>Message:</label> <textarea name="message" required></textarea></p>
        <p><input type="submit" value="Send" /></p>
    </form>

    <?php include 'inc/footer.inc.php'?>
</body>
</html>

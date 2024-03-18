<!DOCTYPE html>
<html lang="en">
<head>
<?php include 'inc/head.inc.php';?>
    <title>Contact Form</title>
</head>
<body>
    <?php include 'inc/nav.inc.php'?>

    <form method="POST" id="contact-form">
        <h2>Contact us</h2>
        <p><label>First Name:</label> <input name="name" id="name" type="text" required /></p>
        <p><label>Email Address:</label> <input name="email" id="email" type="email" required /></p>
        <p><label>Message:</label> <textarea name="message" id="message" required></textarea></p>
        <p><input type="submit" id="submit_email" value="Send" /></p>
    </form>
    <script src="js/send_email.js"></script>
    <?php include 'inc/footer.inc.php'?>
</body>
</html>

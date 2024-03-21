<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Little Haven Shopee Customer Service</title>
<?php
  include 'inc/head.inc.php';
?>
</head>
<body>
<?php
  include 'inc/nav.inc.php';
?>

<div class="customer-service-container d-flex justify-content-center align-items-center min-vh-100">
  <div class="customer-service-content card p-4">
    <h2 class="text-center mb-4">Welcome to Little Haven Shopee Customer Service</h2>
    <p>We're happy to help with any questions you may have.  Choose an option below to get started:</p>
    <div class="row">
      <div class="col-md-6">
        <div class="service-option">
          <i class="fas fa-headset"></i> <h3>Live Chat</h3>
          <p>Connect with a customer service representative in real-time.</p>
          <a href="#" class="btn btn-primary">Start Chat</a>
        </div>
      </div>
      <div class="col-md-6">
        <div class="service-option">
          <i class="fas fa-envelope"></i> <h3>Email Support</h3>
          <p>Send us an email and we'll get back to you as soon as possible.</p>
          <a href="email_support.php" class="btn btn-secondary">Send Email</a>
        </div>
      </div>
    </div>
    <hr>
    <div class="additional-resources">
      <h4>Additional Resources</h4>
      <ul class="list-group">
        <li class="list-group-item"><a href="faq.php">Frequently Asked Questions (FAQ)</a></li>
        <li class="list-group-item"><a href="#">Order Tracking</a></li>
        <li class="list-group-item"><a href="#">Return & Refund Policy</a></li>
      </ul>
    </div>
  </div>
</div>

<?php
  include 'inc/footer.inc.php';
?>

<script src="scripts.js"></script>
</body>
</html>

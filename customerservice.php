<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Little Haven Shoppe Customer Service</title>
<?php
  include 'inc/header.inc.php'; 
  include 'inc/head.inc.php';
?>
</head>
<body>
<?php
  include 'inc/nav.inc.php';
?>

<div class="customer-service-container d-flex justify-content-center align-items-center min-vh-100">
  <div class="customer-service-content card p-4 text-center">
    <h2 class="text-center mb-4">Welcome to Little Haven Shoppe Customer Service</h2>
    <p>We're happy to help with any questions you may have.<br></span> Choose an option below to get started:</p>
   <div class="container"> <!-- Ensure it's within a container for proper alignment -->
  <div class="row justify-content-center"> <!-- This makes the child elements center aligned -->
    <div class="col-md-6">
      <div class="service-option text-center"> <!-- Added text-center for text alignment -->
        <i class="fas fa-envelope"></i>
        <h3>Email Support</h3>
        <p>Send us an email and we'll get back to you as soon as possible.</p>
        <a href="email_support.php" class="btn btn-secondary">Send Email</a>
      </div>
    </div>
  </div>
</div>

    <hr>
    <div class="additional-resources">
      <h4>Additional Resources</h4>
      <ul class="list-group">
        <li class="list-group-item"><a href="faq.php">Frequently Asked Questions (FAQ)</a></li>
        <li class="list-group-item"><a href="returnpolicy.php">Return & Refund Policy</a></li>
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

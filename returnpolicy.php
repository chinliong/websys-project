<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Little Haven Shoppe Customer Service</title>
<?php
 include 'inc/head.inc.php';
?>
</head>
<body>
  <main>
<?php
  include 'inc/header.inc.php';
  include 'inc/nav.inc.php';
  ?>
  
<div class="return-policy">  <h2>Return Eligibility</h2>  <ul>
    <li>Products: You can return any unopened, unused, and undamaged product within [Number] days of purchase.</li>
    <li>Receipt: You must include a copy of your receipt with your return.</li>
    <li>Exceptions: Some products, such as digital downloads or perishable items, may not be eligible for return. These exceptions will be clearly marked on the product page.</li>
  </ul>
</div>

<div class="return"> <h2>Refund Process</h2>

  <ol>
    <li>Contact Us:  To initiate a return, please contact us at [Email Address] or by phone at [Phone Number] within the return eligibility timeframe.</li>
    <li>Return Authorization: We will provide you with a return authorization number (RAN) and instructions on how to return the product.</li>
    <li>Return Shipment: You are responsible for the cost of returning the product.</li>
    <li>Inspection: Once we receive the returned product, we will inspect it to ensure it meets the return eligibility criteria.</li>
    <li>Refund: If the return is approved, we will issue a full refund to the original payment method within 2 business days.</li>
  </ol>
</div>

<div class="contact-information">  <h2>Additional Information</h2>
  <p>We reserve the right to modify this return and refund policy at any time. We recommend reviewing this policy periodically for any changes.</p>
  <p>For any questions regarding our return and refund policy, please contact us at [Email Address] or by phone at [Phone Number].</p>
</div>

<?php
  include 'inc/footer.inc.php';
?>
   </main>
</body>

</html>

<?php
  session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Little Haven Shoppe Customer Service</title>
<?php
 include 'inc/head.inc.php';
  include 'inc/header.inc.php';
?>
<?php
  include 'inc/nav.inc.php';
?>
  <h1>Frequently Asked Questions</h1>
  <div class="faq-container">
    <?php

      // Define FAQ data (replace with your data)
      $faqs = array(
        "Is it safe to buy and sell on Little Haven Shoppe?" => "<p>Absolutely! We prioritize creating a trustworthy environment and offer features to ensure a safe shopping experience for everyone.</p>",
        "How do I find the products I'm looking for?" => "<p>You can browse through our categories, use the search bar, or explore curated collections.</p>",
        "How do I start selling on Little Haven Shoppe?" => "<p>Creating an account and setting up your shop is easy! We provide a user-friendly platform to manage your listings and sales.</p>",
        "What payment methods do we accept?" => "<p>We offer a variety of secure payment options for your convenience.</p>"
      );

    ?>
    
    <script>
      function toggleFAQ(faq) {
        const answer = faq.nextElementSibling;
        answer.style.display = answer.style.display === 'none' ? 'block' : 'none';
      }
    </script>

    <?php foreach ($faqs as $question => $answer) : ?>
      <p class="faq-question"><?php echo $question; ?></p>
      <p class="faq-answer"><?php echo $answer; ?></p>
    <?php endforeach; ?>
  </div>
  <?php include "inc/footer.inc.php"; ?>
</body>

</html>



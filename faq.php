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
  <h1>Frequently Asked Questions</h1>
  <div class="faq-container">
    <?php

    // Define FAQ data (replace with your data)
    $faqs = array(
      "Is it safe to buy and sell on Little Haven Shoppe?" => "Absolutely! We prioritize creating a trustworthy environment and offer features to ensure a safe shopping experience for everyone.",
      "How do I find the products I'm looking for?" => "You can browse through our categories, use the search bar, or explore curated collections.",
      "How do I start selling on Little Haven Shoppe?" => "Creating an account and setting up your shop is easy! We provide a user-friendly platform to manage your listings and sales.",
      "What payment methods do we accept?" => "We offer a variety of secure payment options for your convenience."
    );

    ?>
    
    <script>
      function toggleFAQ(faq) {
        const answer = faq.nextElementSibling;
        answer.style.display = answer.style.display === 'none' ? 'block' : 'none';
      }
    </script>

    <?php foreach ($faqs as $question => $answer) : ?>
      <div class="faq" onclick="toggleFAQ(this)">
        <div class="faq-question"><?php echo $question; ?></div>
      </div>
      <div class="faq-answer"><?php echo $answer; ?></div>
    <?php endforeach; ?>
  </div>

</body>
</html>



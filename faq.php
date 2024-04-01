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
<?php
  include 'inc/nav.inc.php';
?>
  <h1>Frequently Asked Questions</h1>
  <div class="faq-container">
    <?php

    // Define FAQ data (replace with your data)
    $faqs = array(
      "What is this FAQ page about?" => "This is an interactive FAQ page built using PHP and JavaScript.",
      "How can I customize this page?" => "You can edit the questions and answers in the PHP code. Consider using a CMS for large-scale FAQ management.",
      "Are there other ways to create FAQ pages?" => "Yes, databases and CMS with built-in FAQ functionalities are popular options."
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



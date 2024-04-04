<!DOCTYPE html>
<html lang="en">
<head>
  <?php include 'inc/head.inc.php'; ?>
  <title>Contact Form</title>
</head>
<body>
  <?php include 'inc/nav.inc.php';
        include 'inc/header.inc.php'; 
  ?>
<main>
  <section id="contact">
    <h2>Contact Us</h2>
    <form method="POST" id="contact-form">
      <fieldset>
        <legend style="color: white;">Your Information</legend>
        <div class="form-group">
          <label for="name">First Name:</label>
          <input type="text" id="name" name="name" required>
        </div>
        <div class="form-group">
          <label for="email">Email Address:</label>
          <input type="email" id="email" name="email" required>
        </div>
      </fieldset>
      <fieldset>
        <legend style="color: white;">Your Message</legend>
        <div class="form-group">
          <label for="message">Message:</label>
          <textarea id="message" name="message" required></textarea>
        </div>
      </fieldset>
      <div class="form-group">
        <input type="submit" value="Send">
      </div>
    </form>
  </section>
</main>
  <script src="js/send_email.js"></script>
  <?php include 'inc/footer.inc.php'; ?>
</body>
</html>

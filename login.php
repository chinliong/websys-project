<!DOCTYPE html>
<html lang="en">

<head>
  <title>Little Haven Shoppe Login</title>
  <?php
  include 'inc/header.inc.php';
  ?>
  <?php
  include 'inc/head.inc.php';
  ?>
  <script src="scripts.js" defer></script>
  <script src="https://www.google.com/recaptcha/api.js" async defer></script>

</head>

<body>
  <?php
  include 'inc/nav.inc.php';
  ?>
  <div class="alert-area">
    <?php if (!empty($_SESSION['errorMsg'])) : ?>
      <div id="error-alert" class="alert alert-danger" role="alert"><?= $_SESSION['errorMsg']; ?></div>
      <?php unset($_SESSION['errorMsg']); ?>
    <?php endif; ?>
  </div>

  <main>
    <section id="login">

      <section>
        <div class="row">
          <h2 class="text-center mb-4">Login to Your Account</h2>
          <form action="process_login.php" method="post">
            <div class="form-group col-md-6 col-sm-12">
              <label for="email" class="form-label">Email</label>
              <input type="email" class="form-control" id="email" name="email" placeholder="Enter email" required>
            </div>
            <div class="form-group col-md-6 col-sm-12">
              <label for="pwd" class="form-label">Password</label>
              <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Enter your password" required>
            </div>
            <div class="form-check mb-3">
              <input type="checkbox" class="form-check-input" id="remember-me" name="remember_me">
              <label for="remember-me" class="form-check-label">Remember Me</label>
            </div>
            <div class="g-recaptcha" data-sitekey="6LfQNJ8pAAAAAJnPvUiOcFTvlB2a2N2xbPZbhC5e"></div>

            <button type="submit" class="btn btn-primary submitt-btn">Login</button>
            <a href="#" class="forgot-password">Forgot Password?</a>
          </form>
          <p class="mt-3 text-center">Don't have an account? <a href="register.php">Sign Up</a></p>
    </div>
    </section>

    <script>
      // Define onSubmit function to handle reCAPTCHA verification
      function onSubmit(token) {
        document.querySelector('form').submit(); // Submit the form
      }
    </script>

    <?php
    include 'inc/footer.inc.php';
    ?>

  </section>
  </main>
</body>

</html>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Little Haven Shopee Login</title>
<?php
  include 'inc/head.inc.php';
?>
</head>
<body>
<?php
  include 'inc/nav.inc.php';
?>

<div class="login-container d-flex justify-content-center align-items-center min-vh-100">
  <div class="login-form card p-4">
    <h2 class="text-center mb-4">Login to Your Account</h2>
    <form action="process_login.php" method="post">
      <div class="form-group">
        <label for="uname" class="form-label">Username or Email</label>
        <input type="text" class="form-control" id="uname" name="uname"
               placeholder="Enter username or email" required>
      </div>
      <div class="form-group">
        <label for="pwd" class="form-label">Password</label>
        <input type="password" class="form-control" id="pwd" name="pwd"
               placeholder="Enter your password" required>
      </div>
      <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" id="remember-me" name="remember_me">
        <label for="remember-me" class="form-check-label">Remember Me</label>
      </div>
      <button type="submit" class="btn btn-primary">Login</button>
      <a href="#" class="forgot-password">Forgot Password?</a>
    </form>
    <p class="mt-3 text-center">Don't have an account? <a href="register.php">Sign Up</a></p>
  </div>
</div>

<?php
  include 'inc/footer.inc.php';
?>

<script src="scripts.js"></script>
</body>
</html
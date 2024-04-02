<!DOCTYPE html>
<html lang="en">

<head>
  <title>Ferris Wheel - Register</title>
  <?php   include 'inc/header.inc.php'; 
          include 'inc/head.inc.php'; ?>
</head>

<body>
  <?php include "inc/nav.inc.php"; ?>

  <main class="container py-5">
  <div class="row d-flex justify-content-center">
    <div class="col-md-8">
      <h1 class="text-center mb-4">Join the Ferris Wheel Family!</h1>
      <p class="text-center">Already a member? <a href="login.php">Sign In Here</a></p>

      <form action="process_register.php" method="post" class="row g-3">

        <div class="col-md-6">
          <label for="uname" class="form-label">Username</label>
          <input maxlength="45" type="text" class="form-control" id="uname" name="uname" placeholder="Enter a unique username (max 45 characters)">
        </div>

          <div class="col-md-6">
            <label for="email" class="form-label">Email Address</label>
            <input maxlength="45" type="email" class="form-control" id="email" name="email" placeholder="Enter your email">
          </div>

          <div class="col-md-6">
            <label for="pwd" class="form-label">Password</label>
            <input type="password" class="form-control" id="pwd" name="pwd" placeholder="Create a strong password">
          </div>

          <div class="col-md-6">
            <label for="pwd_confirm" class="form-label">Confirm Password</label>
            <input type="password" class="form-control" id="pwd_confirm" name="pwd_confirm" placeholder="Re-enter your password">
          </div>

          <div class="col-md-12">
            <div class="form-check">
              <input type="checkbox" class="form-check-input" name="agree" id="agree">
              <label class="form-check-label" for="agree">Agree to Terms & Conditions</label>
            </div>
          </div>

          <div class="col-md-12 text-center">
            <button type="submit" class="btn btn-primary">Join Now!</button>
          </div>

        </form>
      </div>
    </div>
  </main>

  <?php include "inc/footer.inc.php"; ?>
</body>

</html>

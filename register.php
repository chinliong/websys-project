<!DOCTYPE html>
<html lang="en">
<head>
<title>Ferris wheel</title>
    <?php
        include 'inc/head.inc.php';
    ?>
</head>

<body>
<?php
include "inc/nav.inc.php";
?>
<main class="container">
<h1>Member Registration</h1>
<p>
For existing members, please go to the
<a href="#">Sign In page btw this link is broken. will fix soon</a>.
</p>
<form action="process_register.php" method="post">
<div class="col-md-3">
<label for="uname" class="form-label">Username</label>
<input maxlength="45" type="text" class="form-control" id="uname" name="uname"
placeholder="Enter a username (make it unique) :-)">
</div>
<div class="col-md-3">
<label for="email" class="form-label">Email:</label>
<input maxlength="45" type="email" class="form-control" id="email" name="email"
placeholder="Enter email">
</div>
<div class="col-md-3">
<label for="pwd" class="form-label">Password:</label>
<input type="password" class="form-control" id="pwd" name="pwd"
placeholder="Enter password">
</div>
<div class="col-md-3">
<label for="pwd_confirm" class="form-label">Confirm Password:</label>
<input type="password"  class="form-control" id="pwd_confirm" name="pwd_confirm"
placeholder="Confirm password">
</div>
<div class="col-md-3 form-check">
<label class="form-check-label">
<input type="checkbox" class="form-check-input" name="agree">
Agree to terms and conditions.
</label>
</div>
<div class="col-md-3">
<button type="submit" class="btn btn-primary">Submit</button>
</div>
</form>
</main>
<?php
include "inc/footer.inc.php";
?>
</body>

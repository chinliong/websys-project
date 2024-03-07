<!DOCTYPE html>
<html lang="en">
<head>
<title>Ferris wheel login page</title>
    <?php
        include 'inc/head.inc.php';
    ?>
</head>

<body>
<?php
include "inc/nav.inc.php";
?>
<main class="container">
<h1>Member Login</h1>
<p>
Existing members log in here, for new members, please go to the 
<a href="/register.php">Member Registration Page</a>.
</p>
<form action="process_login.php" method="post">
<div class="col-md-3">
<label for="uname" class="form-label">Username</label>
<input maxlength="45" type="text" class="form-control" id="uname" name="uname"
placeholder="Enter username here">
</div>
<div class="col-md-3">
<label for="pwd" class="form-label">Password:</label>
<input type="password" class="form-control" id="pwd" name="pwd"
placeholder="Enter your password">
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

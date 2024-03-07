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
    <section id="list-product">
    <h1>New Listing</h1>
    <p>
        Upload a new listing here!
    </p>
        <form action="process_login.php" method="post">
            <div class="row">
                <div class="col-md-6 col-sm-12">
                    <label for="pname" class="form-label">Product Name</label>
                    <input maxlength="255" type="text" class="form-control" id="pname" name="pname"
                    placeholder="Enter your product name">
                </div>
                    <div class="col-md-6 col-sm-12">
                    <label for="price" class="form-label">Price (S$)</label>
                    <input type="price" class="form-control" id="price" name="price"
                    placeholder="How much does this sell for?">
                </div>
                <div class="col-sm-12">
                    <label for="pimage" class="form-label">Upload a picture here</label>
                    <input type="file" class="form-control" id="file" name="file">
                </div>
                    <div class="col-sm-12">
                    <button type="submit" class="btn btn-primary">List it!</button>
                </div>
            </div>
        </form>
    </section>
</main>
<?php
include "inc/footer.inc.php";
?>
</body>

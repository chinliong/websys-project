<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
<title>Shoe-seller</title>
    <?php 
        include 'inc/head.inc.php';
    ?>
</head>
<body>

    <?php 
        include 'inc/nav.inc.php'; 
    ?>


    <?php 
        include 'inc/header.inc.php'; 
    ?>
  <div class="container">
    
    
    <div class="content mt-5">
        <h1>Welcome To Our<br><span>Little Haven Shoppe</span><br>Online Store</h1>
        <a href="#" class="btn btn-primary mt-3">Join Us</a>

        <div class="row mt-4">
            <!-- Products Section -->
            <h2 class="w-100">Featured Products Testing1</h2>
            <!-- Product Item -->
            <div class="col-md-4">
                <div class="card">
                    <img src="path/to/product1.jpg" class="card-img-top" alt="Product 1">
                    <div class="card-body">
                        <h3 class="card-title">Product 1</h3>
                        <p class="card-text">$19.99</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <img src="path/to/product2.jpg" class="card-img-top" alt="Product 2">
                    <div class="card-body">
                        <hh3 class="card-title">Product 2</h3>
                        <p class="card-text">$19.99</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="path/to/product3.jpg" class="card-img-top" alt="Product 3">
                    <div class="card-body">
                        <h3 class="card-title">Product 3</h3>
                        <p class="card-text">$19.99</p>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card">
                    <img src="path/to/product4.jpg" class="card-img-top" alt="Product 4">
                    <div class="card-body">
                        <h3 class="card-title">Product 4</h3>
                        <p class="card-text">$19.99</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="images/logo.png" class="card-img-top" alt="Product 5">
                    <div class="card-body">
                        <h3 class="card-title">Product 5</h3>
                        <p class="card-text">$19.99</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="path/to/product4.jpg" class="card-img-top" alt="Product 6">
                    <div class="card-body">
                        <h3 class="card-title">Product 6</h3>
                        <p class="card-text">$19.99</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="path/to/product4.jpg" class="card-img-top" alt="Product 7">
                    <div class="card-body">
                        <h3 class="card-title">Product 7</h3>
                        <p class="card-text">$19.99</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="path/to/product4.jpg" class="card-img-top" alt="Product 8">
                    <div class="card-body">
                        <h3 class="card-title">Product 8</h3>
                        <p class="card-text">$19.99</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <img src="path/to/product4.jpg" class="card-img-top" alt="Product 9">
                    <div class="card-body">
                        <h3 class="card-title">Product 9</h3>
                        <p class="card-text">$19.99</p>
                    </div>
                </div>
            </div>
            <!-- Repeat for other products, adjusting col-md-4 as necessary for your design -->
        </div>
    </div>
</div>
<script>
    window.addEventListener('scroll', () => {
      const navbar = document.querySelector('.navbar');
      const sticky = navbar.offsetTop;
  
      if (window.pageYOffset >= sticky) {
        navbar.classList.add('sticky');
      } else {
        navbar.classList.remove('sticky');
      }
    });
  </script>
<footer>
    <p><em>Copyright &copy; 2024 Little Haven Shopee Pte. Ltd.</em></p>
</footer>


</body>
</html>







        
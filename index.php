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
        include 'db_con.php';
        include 'inc/header.inc.php'; 

        $sql = $conn->prepare("SELECT product_id, product_name, product_image, price FROM ferris_wheel.product_table");
        $sql->execute();
        $result = $sql->get_result();
    ?>
  <div class="container">
    
    
    <div class="content mt-5">
    <h1>Welcome To Our Little Haven Shopee Online Store</h1>
    <h4>Featured Products</h4>



        <div class="container">
        <div class="row mt-4">
            <?php while($product = $result->fetch_assoc()): ?>
                <div class="col-md-4">
                    <div class="card">
                        <a href="product_page.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">
                        <img src="/images/<?php echo htmlspecialchars($product['product_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                        <div class="card-body">
                            <h3 class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></h3>
                        </a>
                            <p class="card-text">$<?php echo htmlspecialchars($product['price']); ?></p>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
            <!-- <div class="col-md-4">
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
            </div> -->
            <!-- Repeat for other products, adjusting col-md-4 as necessary for your design -->
        <!-- </div>
    </div>
</div> -->
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







        
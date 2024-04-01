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
    <div class="alert-area">
        <?php if (!empty($_SESSION['successMsg'])) : ?>
            <div id="success-alert" class="alert alert-success" role="alert"><?= $_SESSION['successMsg']; ?></div>
            <?php unset($_SESSION['successMsg']); ?>
        <?php endif; ?>
        <script>
            // Wait for the DOM to be loaded
            document.addEventListener("DOMContentLoaded", function() {
                // If the success alert exists, set a timeout to hide it
                var successAlert = document.getElementById("success-alert");
                if (successAlert) {
                    setTimeout(function() {
                        successAlert.style.display = "none";
                    }, 3000); // Hide after 3 seconds
                }
            });
        </script>
    </div>

    <div class="container">
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="images/Rari.jpg" class="d-block w-100" alt="car">
                </div>
                <div class="carousel-item">
                    <img src="images/Rari.jpg" class="d-block w-100" alt="car">
                </div>
                <div class="carousel-item">
                    <img src="images/Rari.jpg" class="d-block w-100" alt="car">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

    </div>

    <div class="content mt-5">
        <h1>Welcome To Our Little Haven Shopee Online Store</h1>
        <h4>Featured Products</h4>

        <div class="container">
            <div class="row mt-4">
                <?php while ($product = $result->fetch_assoc()) : ?>
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
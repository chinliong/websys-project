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

    $sql = $conn->prepare("SELECT 
    p.product_id, 
    p.product_name, 
    p.product_image, 
    p.price, 
    p.cat_id, 
    p.user_id
  FROM 
    product_table p
  INNER JOIN 
    (SELECT MIN(product_id) AS min_product_id, cat_id
     FROM product_table
     GROUP BY cat_id) AS subquery
  ON 
    p.product_id = subquery.min_product_id
  ");
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
                    <img src="images/iphone.jpg" class="d-block w-100" alt="Phone">
                </div>
                <div class="carousel-item">
                    <img src="images/ps5.jpg" class="d-block w-100" alt="ps5">
                </div>
                <div class="carousel-item">
                    <img src="images/ipad.jpg" class="d-block w-100" alt="ipad">
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
        <h1>Welcome To Our Little Haven Shoppe Online Store</h1>
        <h4 id="fh4">Featured</h4>
        <hr class="linefeed">
        <p id="fmsg">Take a look at the highlighted products showcased below!</p>
        <div class="container">
            <div class="row mt-4">
                <?php
                while ($product = $result->fetch_assoc()) :
                ?>
                    <div class="col-md-4 col-sm-12 lcard">
                        <div class="card">
                            <a href="product_page.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">
                                <img src="/images/<?php echo htmlspecialchars($product['product_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                <div class="card-body">
                            </a>
                            <p class="card-title"><?php echo htmlspecialchars($product['product_name']); ?></p>
                            <p class="card-text">$<?php echo htmlspecialchars($product['price']); ?></p>
                        </div>
                    </div>
                
            </div>
        <?php endwhile; ?>
        </div>
    </div>
    <section id="deals">
        <h4 id="dh4">Upcoming Deals</h4>
        <article class="row parent">
            <div class="col-md-4 col-sm-12 dealcard">
                <h5 class="dh5"><i class="fa fa-money" aria-hidden="true"></i>Start Spending September</h5>
                <p class="dmsg">Get 10% off all produts listed this September!</p>
            </div>
            <div class="col-md-4 col-sm-12 dealcard">
                <h5 class="dh5"><i class="fa fa-check" aria-hidden="true"></i> No Nike November</h5>
                <p class="dmsg">Get 100% off all Nike products this November!</p>
            </div>
            <div class="col-md-4 col-sm-12 dealcard">
                <h5 class="dh5"><i class="fa fa-credit-card" aria-hidden="true"></i> Double Deposit December</h5>
                <p class="dmsg">Any amount of money deposited will be doubly credited to your wallet this December!</p>
            </div>
        </article>
    </section>
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
        <p><em>Copyright &copy; 2024 Little Haven Shoppe Pte. Ltd.</em></p>
    </footer>


</body>

</html>
<?php
session_start();
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <title>Shoppe Haven</title>
    <?php
    include 'inc/head.inc.php';
    ?>
</head>
<body>
 <main>
    <div class="intro">
        <h1 class="logo-header">
            <img src="images/cat_chipi.gif" alt="Loading" class="splash-gif">
            <span class="logo">Shoppe</span>
            <span class="logo">Haven</span>
            <img src="images/cat_chipi.gif" alt="Loading" class="splash-gif">
        </h1>
    </div>
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
        <h2 id="fh4">Featured</h2>
        <hr class="linefeed">
        <p id="fmsg">Take a look at the highlighted products showcased below!</p>
        <div class="container">
            <div class="row mt-4">
                <?php
                while ($product = $result->fetch_assoc()) :
                ?>
                    <div class="col-lg-4 col-md-6 col-sm-12 lcard">
                        <div class="card">
                            <a href="product_page.php?id=<?php echo htmlspecialchars($product['product_id']); ?>">
                                <img src="/images/<?php echo htmlspecialchars($product['product_image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($product['product_name']); ?>">
                                </a>   
                            <div class="card-body">
                            <p class="card-title black-words"><?php echo htmlspecialchars($product['product_name']); ?></p>
                            <p class="card-text black-words">$<?php echo htmlspecialchars($product['price']); ?></p>
                            </div>
                    </div>
            </div>
        <?php endwhile; ?>
        </div>
    </div>
    </div>
    <section id="deals">
        <h2 id="dh4">Upcoming Deals</h2>
        <div class="container">
        <article class="row parent">
            <div class="col-md-4 col-sm-12 dealcard">
                <h3 class="dh5"><i class="fa fa-money" aria-hidden="true"></i>Start Spending September</h5>
                <p class="dmsg">Get 10% off all produts listed this September!</p>
            </div>
            <div class="col-md-4 col-sm-12 dealcard">
                <h3 class="dh5"><i class="fa fa-times" aria-hidden="true"></i> No Nike November</h5>
                <p class="dmsg">Get 100% off all Nike products this November!</p>
            </div>
            <div class="col-md-4 col-sm-12 dealcard">
                <h3 class="dh5"><i class="fa fa-credit-card" aria-hidden="true"></i> Double Deposit December</h5>
                <p class="dmsg">Any amount of money deposited will be doubly credited to your wallet this December!</p>
            </div>
        </article>
        </div>
    </section>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const intro = document.querySelector('.intro');
            const logos = document.querySelectorAll('.logo');

                // Animate each logo span
                logos.forEach((logo, idx) => {
                    setTimeout(() => {
                        logo.classList.add('active');
                    }, (idx + 1) * 400);
                });

                // Animate fade-out
                setTimeout(() => {
                    logos.forEach((logo, idx) => {
                        setTimeout(() => {
                            logo.classList.add('fade');
                        }, (idx + 1) * 50);
                    });
                }, 2000);

                // Hide splash screen
                setTimeout(() => {
                    intro.style.opacity = '0';
                    intro.style.visibility = 'hidden';
                }, 3000);
            });
    </script>
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
        <script type="text/javascript">
        var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
        (function(){
        var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
        s1.async=true;
        s1.src='https://embed.tawk.to/660e7de6a0c6737bd1285037/1hqk9njno';
        s1.charset='UTF-8';
        s1.setAttribute('crossorigin','*');
        s0.parentNode.insertBefore(s1,s0);
        })();
        </script>
    <footer>
        <p><em>Copyright &copy; 2024 Little Haven Shoppe Pte. Ltd.</em></p>
    </footer>

    </main>
</body>

</html>
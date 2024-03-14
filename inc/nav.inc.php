<?php
session_start();
?>
    <nav class="navbar navbar-expand-sm bg-secondary" data-bs-theme="dark">
        <div class="container-fluid">
        <a class="navbar-brand" href="#"><img src="images/logo.jpg"/></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link active" href="../index.php">Home</a>
        </li>
        <ul class="navbar-nav ms-auto">
            <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) : ?>
                <li class="nav-item">
                <a class="nav-link" href="../new_listing.php">New Listings</a>
                </li>
                <li class="nav-item d-none d-lg-block">
                    <a class="nav-link" href="../profile.php"><i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../logout.php"><i class="fas fa-sign-out-alt d-none d-lg-inline"></i> Logout <span class="d-inline d-lg-none"> (<?php echo htmlspecialchars($_SESSION['uname']); ?>)</span></a>
                </li>
            <?php else : ?>
                <li class="nav-item">
                    <a class="nav-link" href="../register.php"><i class="fas fa-user-plus d-none d-lg-inline"></i> Register</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../login.php"><i class="fas fa-sign-in-alt d-none d-lg-inline"></i> Login</a>
                </li>
            <?php endif; ?>
        </ul>
        <!-- <li class="nav-item">
            <a class="nav-link" href="../new_listing.php">New Listing</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../profile.php">Profile</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../register.php">Register</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../login.php">Login here</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../logout.php">Logout</a>
        </li>
        <li class="nav-item">
            <a class="nav-link" href="../new_listing.php">new_listing</a>
        </li> -->
        <li class="nav-item">   
            <form action="../process_search.php" method="post">
                <input maxlength="55" type="text" class="form-control" id="search" name="search" placeholder="Search for keywords">
                <select name="cat">
                    <option value="all_cats_in_db">All categories</option>
                    <?php
                        include "db_con.php";

                        // $config = parse_ini_file('/var/www/private/db-config.ini');
                        // $conn = new mysqli(
                        //     $config['servername'],
                        //     $config['username'],
                        //     $config['password'],
                        //     $config['dbname']
                        // );

                        $stmt = $conn->prepare("SELECT * FROM product_category");
                        $stmt->execute();
                        $navigation_menu_categories_results = $stmt->get_result();
                        if($navigation_menu_categories_results->num_rows > 0) {
                            while($navigation_row_results_options = $navigation_menu_categories_results->fetch_assoc()) {
                                echo "<option value='".$navigation_row_results_options['cat_id']."'>".$navigation_row_results_options['cat_name']."</option>";
                            }
                        } else{
                            echo "<option value=''>No categories found</option>";
                        }
                        $stmt->close();
                        $conn->close();
                    ?>
                </select>
                <button type="submit">Submit</button>
            </form>
        </li>

        </ul>
        </div>
        </div>
    </nav>
<?php
session_start();
if ((isset($_SESSION['loggedin']) && $_SESSION['loggedin'])) {
  include 'db_con.php';

  $stmt = $conn->prepare("SELECT username FROM user_table WHERE user_id = ?");
  $stmt->bind_param("i", $_SESSION['userid']);
  $stmt->execute();

  $uname_results_fetched = $stmt->get_result();
  $uname_row = $uname_results_fetched->fetch_assoc();
  $current_logged_in_username = $uname_row['username'];
  echo "<script>console.log($current_logged_in_username);</script>";
  $stmt->close();
  $conn->close();
}
?>
<nav class="navbar navbar-expand-sm bg-dark navbar-dark fixed-top">
  <div class="container-fluid">

    <a class="navbar-brand" href="#"><img src="images/logo.png" alt="Brand Logo"></a>

    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <div class="collapse navbar-collapse" id="navbarNav">

      <ul class="navbar-nav me-auto">
        <li class="nav-item">
          <a class="nav-link active" href="../index.php">Home</a>
        </li>
        </ul>

      <ul class="navbar-nav ms-auto">

        <?php if (isset($_SESSION['loggedin']) && $_SESSION['loggedin']) : ?>
          <li class="nav-item">
            <a class="nav-link" href="../listings.php"><i class="fa fa-book" aria-hidden="true"></i> Manage Listings</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../new_listing.php"><i class="fas fa-plus-circle"></i> New Listing</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user"></i> <?php echo htmlspecialchars($current_logged_in_username); ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="../profile.php"><i class="fa fa-address-card" aria-hidden="true"></i> Profile</a></li>
              <?php
              if (($_SESSION['role'] == 'a')) {
                echo "<li><a class='dropdown-item' href='../console.php'><i class='fa fa-cog' aria-hidden='true'></i> Dashboard</a></li>";
              } else{
                echo '<li><a class="dropdown-item" href="../finance.php"><i class="fa fa-university" aria-hidden="true"></i> Balance</a></li>';
              }
              ?>
              <li><a class="dropdown-item" href="../transaction_history.php"><i class="fas fa-history"></i> Transaction History</a></li>
              <li><a class="dropdown-item" href="../logout.php"> Logout</a></li>
            </ul>

          </li>
          <li class="nav-item">
          <a class="nav-link" href="../shopping_cart.php"><i class="fas fa-shopping-cart"></i> Shopping Bag </a>
          </li>

        <?php else : ?>
          <li class="nav-item">
            <a class="nav-link" href="../register.php"><i class="fas fa-user-plus"></i> Register</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="../login.php"><i class="fas fa-sign-in-alt"></i> Login</a>
          </li>

        <?php endif; ?>

        <li class="nav-item">
          <a class="nav-link" href="../customerservice.php"><i class="fas fa-headset"></i> Customer Service</a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../aboutus.php"><i class="fas fa-solid fa-book-open"></i> About Us </a>
        </li>

        <li class="nav-item">
          <a class="nav-link" href="../findus.php"><i class="fas fa-regular fa-map"></i> Find Us </a>
        </li>



        



        <li class="nav-item d-flex">
          <form action="../process_search.php" method="post" class="d-flex">
            <input maxlength="55" type="text" class="form-control me-2" id="search" name="search" placeholder="Search something...  ">
            <select name="cat" class="form-select">
              <option value="all_cats_in_db">All Categories</option>
              <?php
              include "db_con.php";

              $stmt = $conn->prepare("SELECT * FROM product_category");
              $stmt->execute();
              $navigation_menu_categories_results = $stmt->get_result();

              if ($navigation_menu_categories_results->num_rows > 0) {
                while ($navigation_row_results_options = $navigation_menu_categories_results->fetch_assoc()) {
                  echo "<option value='" . $navigation_row_results_options['cat_id'] . "'>" . $navigation_row_results_options['cat_name'] . "</option>";
                }
              } else {
                echo "<option value=''>No categories found</option>";
              }
              $stmt->close();
              $conn->close();
              ?>
            </select>
            <button type="submit" class="btn btn-primary">Search</button>
          </form>
        </li>

      </ul>

    </div>

  </div>
</nav>
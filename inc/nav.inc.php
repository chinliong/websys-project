<?php
session_start();
?>
<nav
class="navbar navbar-expand-sm bg-dark navbar-dark">
  <div class="container-fluid">

    <a class="navbar-brand" href="#"><img src="images/logo.png" alt="Brand Logo" width="150" height="50"></a>

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
            <a class="nav-link" href="../new_listing.php">New Listings</a>
          </li>
          <li class="nav-item">
          <a class="nav-link" href="../shopping_cart.php"> Shopping Cart </a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fas fa-user"></i> <?php echo htmlspecialchars($_SESSION['username']); ?>
            </a>
            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
              <li><a class="dropdown-item" href="../profile.php">Profile</a></li>
              <li><a class="dropdown-item" href="../logout.php">Logout</a></li>
            </ul>

          </li>
          <li class="nav-item">
          <a class="nav-link" href="../shopping_cart.php"> Shopping Cart </a>
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
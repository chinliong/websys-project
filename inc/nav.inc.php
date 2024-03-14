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
        <li class="nav-item">
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
        </li>
        
        <li class="nav-item">   
            <form action="../process_search.php" method="post">
                <input maxlength="55" type="text" class="form-control" id="search" name="search" placeholder="Search for keywords">
                <button type="submit">Submit</button>
            </form>
        </li>
        </ul>
        </div>
        </div>
    </nav>
<?php
session_start();
include 'db_con.php'; // Ensure this path is correct

function uploadProductImage($file)
{
    $uploadDir = 'images/';
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    $fileName = basename($file['name']);
    $fileTmpPath = $file['tmp_name'];
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $newFileName = md5(time() . rand()) . '.' . $fileExtension;
    $destPath = $uploadDir . $newFileName;

    if (in_array($fileExtension, $allowedExtensions) && $file['error'] === 0) {
        if (move_uploaded_file($fileTmpPath, $destPath)) {
            return $newFileName; // Correctly return the new filename
        } else {
            return null; // Return null to indicate failure
        }
    }
    return null; // Return null for unsupported file type or upload error
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['product_id'])) {
    // Assume all necessary inputs are set and sanitized for simplicity
    $product_id = $_POST['product_id'];
    $product_name = $_POST['product_name'];
    $price = $_POST['price'];
    $cat_id = $_POST['cat_id'];
    $user_id = $_POST['user_id'];

    $uploadedFileName = $_POST['existing_image']; // Fallback to existing image
    if (isset($_FILES['product_image']) && $_FILES['product_image']['error'] === 0) {
        $tempFileName = uploadProductImage($_FILES['product_image']);
        if ($tempFileName !== null) {
            $uploadedFileName = $tempFileName; // Update only if upload succeeds
        } else {
            echo "Image upload failed.";
            exit;
        }
    }

    $sql = "UPDATE product_table SET product_name = ?, price = ?, cat_id = ?, user_id = ?, product_image = ? WHERE product_id = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("sdiiss", $product_name, $price, $cat_id, $user_id, $uploadedFileName, $product_id);
        if (!$stmt->execute()) {
            echo "Error updating record: " . $conn->error;
        } else {
            header('Location: console.php?message=Product updated successfully');
            exit;
        }
    } else {
        echo "Error preparing statement: " . $conn->error;
    }
    $stmt->close();
}

// Load existing product data for editing
if (isset($_GET['product_id'])) {
    $product_id = $_GET['product_id'];
    $sql = "SELECT * FROM product_table WHERE product_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $product_id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows === 0) {
        exit('Product not found.');
    }
    $product = $result->fetch_assoc();
} else {
    header('Location: console.php');
    exit;
}

// Fetch categories for the dropdown
$categoryQuery = "SELECT cat_id, cat_name FROM product_category";
$categoryResult = $conn->query($categoryQuery);
$categories = [];
if ($categoryResult->num_rows > 0) {
    while ($row = $categoryResult->fetch_assoc()) {
        $categories[] = $row;
    }
}

// Fetch the username for the product's user_id
$userId = $product['user_id'];
$userQuery = "SELECT username FROM user_table WHERE user_id = ?";
$stmt = $conn->prepare($userQuery);
$stmt->bind_param("i", $userId);
$stmt->execute();
$stmt->bind_result($username);
$stmt->fetch();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Product</title>
    <?php include 'inc/head.inc.php'; ?>
    <script src="js/console.js" defer></script>
</head>

<body>
    <?php include "inc/nav.inc.php"; ?>
    <main class="container custom-form-container">
        <h2>Edit Product</h2>
        <form action="edit_productconsole.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="product_id" value="<?= htmlspecialchars($product['product_id']); ?>">
            <div class="form-group">
                <label for="product_name">Product Name:</label>
                <input type="text" class="form-control" name="product_name" id="product_name" value="<?= htmlspecialchars($product['product_name']); ?>" required>
            </div>
            <div class="form-group">
                <label for="price">Price:</label>
                <input type="number" class="form-control" step="0.01" name="price" id="price" value="<?= htmlspecialchars($product['price']); ?>" required>
            </div>
            <!-- Category Selection -->
            <div class="form-group">
                <label for="cat_id">Category:</label>
                <select class="form-control" name="cat_id" id="cat_id" required>
                    <?php foreach ($categories as $category) : ?>
                        <option value="<?= $category['cat_id']; ?>" <?= $category['cat_id'] == $product['cat_id'] ? 'selected' : ''; ?>>
                            <?= htmlspecialchars($category['cat_name']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- User ID (non-editable) -->
            <div class="form-group">
                <label for="user_id">Username:</label>
                <input type="text" class="form-control" id="username" value="<?= htmlspecialchars($username); ?>" disabled>
                <!-- Include a hidden field to send the user_id value -->
                <input type="hidden" name="user_id" id="user_id" value="<?= htmlspecialchars($product['user_id']); ?>">
            </div>
            <div class="form-group">
                <label for="product_image">Product Image:</label>
                <input type="file" class="form-control" name="product_image" id="product_image" onchange="previewImage();">
                <small class="form-text text-muted">Current Image:</small>
                <img id="currentImage" src="images/<?= htmlspecialchars($product['product_image']); ?>" alt="Product Image" style="max-width: 300px; height: auto; margin-top:20px; margin: center;">
                <input type="hidden" name="existing_image" value="<?= htmlspecialchars($product['product_image']); ?>">
                <!-- Image Preview Placeholder -->
                <div id="imagePreview"></div>
            </div>
            <button type="submit" class="btn btn-primary">Update Product</button>
        </form>
    </main>
    <?php include "inc/footer.inc.php"; ?>
</body>

</html>
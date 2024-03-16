<?php
    if (isset($_POST['product_id']) && isset($_SESSION['userid'])) {
        $product_id = $_POST['product_id'];
        $user_id = $_SESSION['userid'];

        include 'db_con.php';

        $stmt = $conn->prepare("INSERT INTO cart_table(product_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $product_id, $user_id);

        $stmt->execute();

        $stmt->close();
        $conn->close();
    }
?>
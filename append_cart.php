<?php
    session_start();
    if (isset($_POST['product_id']) && isset($_SESSION['userid'])) {
        $product_id = $_POST['product_id'];
        $user_id = $_SESSION['userid'];

        include 'db_con.php';
        $stmt = $conn->prepare("SELECT user_id FROM product_table WHERE product_id = ?");
        $stmt->bind_param("i", $product_id);
        $stmt->execute();
        $return_result_uid = $stmt->get_result();
        $return_result_for_uid = $return_result_uid->fetch_assoc();
        $uid = $return_result_for_uid['user_id'];
        if($uid != $user_id){
            $stmt = $conn->prepare("INSERT INTO cart_table(product_id, user_id) VALUES (?, ?)");
            $stmt->bind_param("ii", $product_id, $user_id);
            $stmt->execute();
        }
        $stmt->close();
        $conn->close();
    }
?>
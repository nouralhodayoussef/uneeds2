<?php
include 'config.php';
session_start();

if (!$con) {
    die("Connection failed: " . mysqli_connect_error());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_SESSION['user_id'];
    $product_id = $_POST['product_id'];

    $delete_sql = "DELETE FROM shopping_cart WHERE user_id = $user_id AND product_id = $product_id";

    if (mysqli_query($con, $delete_sql)) {
        header('Location: cart_style.php');
        exit;
    } else {
        echo "<script>alert('Failed to remove the product. Please try again.');</script>";
        header('Location: cart_style.php');
        exit;
    }
}

mysqli_close($con);
?>

<?php
include 'config.php';

if (isset($_POST['order_id']) && isset($_POST['new_status'])) {
    $orderId = intval($_POST['order_id']);
    $newStatus = $con->real_escape_string($_POST['new_status']);

    $query = "UPDATE orders SET order_status = '$newStatus' WHERE id = $orderId";

    if ($con->query($query)) {
        echo "Order status updated successfully.";
    } else {
        echo "Error updating order status: " . $con->error;
    }
} else {
    echo "Invalid input.";
}
?>
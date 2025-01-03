<?php
include 'config.php'; 

$sql = "
    SELECT 
        orders.id AS order_id,
        orders.user_id,
        orders.order_status,
        orders.total_price,
        GROUP_CONCAT(order_details.product_id) AS product_ids,
        GROUP_CONCAT(order_details.quantity) AS quantities
    FROM 
        orders
    LEFT JOIN 
        order_details ON orders.id = order_details.order_id
    GROUP BY 
        orders.id";

$result = $con->query($sql);

$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $product_ids = $row['product_ids'] ? $row['product_ids'] : 'No products';
        $quantities = $row['quantities'] ? $row['quantities'] : 'No quantities';
        
        $user_sql = "SELECT name FROM user WHERE id = " . $row['user_id'];
        $user_result = $con->query($user_sql);
        $user_name = ($user_result->num_rows > 0) ? $user_result->fetch_assoc()['name'] : 'Unknown User';
        
        $output .= '
            <tr>
                <td>' . $row['order_id'] . '</td>
                <td>' . $user_name . '</td>
                <td>' . $product_ids . '</td>
                <td>' . $quantities . '</td>
                <td>$' . number_format($row['total_price'], 2) . '</td>
                <td>' . $row['order_status'] . '</td>
            </tr>';
    }
} else {
    $output = '<tr><td colspan="6">No orders found.</td></tr>';
}

echo $output;
?>

<?php
include 'config.php';

$query = "
    SELECT 
    o.id AS order_id,
    u.first_name AS customer,
    o.order_status,
    o.total_price,
    p.name AS product_name,
    od.quantity
FROM orders o
INNER JOIN users u ON o.user_id = u.id
INNER JOIN order_details od ON o.id = od.order_id
INNER JOIN products p ON od.product_id = p.id
ORDER BY o.id ASC
";

$result = $con->query($query);

if ($result->num_rows > 0) {
    $output = '';
    $currentOrderId = null;

    while ($row = $result->fetch_assoc()) {
        // New Order Row
        if ($row['order_id'] !== $currentOrderId) {
            if ($currentOrderId !== null) {
                $output .= '</ul></td>
                    <td>
                        <select class="order-status-select" data-order-id="' . $currentOrderId . '">
                            <option value="Pending"' . ($currentOrderStatus === 'Pending' ? ' selected' : '') . '>Pending</option>
                            <option value="Shipped"' . ($currentOrderStatus === 'Shipped' ? ' selected' : '') . '>Shipped</option>
                            <option value="Delivered"' . ($currentOrderStatus === 'Delivered' ? ' selected' : '') . '>Delivered</option>
                            <option value="Cancelled"' . ($currentOrderStatus === 'Cancelled' ? ' selected' : '') . '>Cancelled</option>
                        </select>
                    </td>
                </tr>';
            }

            $currentOrderId = $row['order_id'];
            $currentOrderStatus = $row['order_status'];

            $output .= '
                <tr>
                    <td>' . $row['order_id'] . '</td>
                    <td>' . $row['customer'] . '</td>
                    <td>
                        <ul>';
        }

        $output .= '<li>' . $row['product_name'] . ' (Qty: ' . $row['quantity'] . ')</li>';
    }

    $output .= '</ul></td>
        <td>
            <select class="order-status-select" data-order-id="' . $currentOrderId . '">
                <option value="Pending"' . ($currentOrderStatus === 'Pending' ? ' selected' : '') . '>Pending</option>
                <option value="Ready"' . ($currentOrderStatus === 'Ready' ? ' selected' : '') . '>Ready</option>
                <option value="Delivered"' . ($currentOrderStatus === 'Delivered' ? ' selected' : '') . '>Delivered</option>
                <option value="Other"' . ($currentOrderStatus === 'Other' ? ' selected' : '') . '>Other</option>
            </select>
        </td>
    </tr>';
} else {
    $output = '<tr><td colspan="5">No orders found</td></tr>';
}

echo $output;
?>
<?php
include 'config.php'; 

$sql = "
    SELECT 
        products.id, 
        products.name, 
        products.description, 
        products.price, 
        products.stock, 
        categories.name AS category_name 
    FROM products 
    LEFT JOIN categories ON products.category_id = categories.id";

$result = $con->query($sql);

$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= '
            <tr>
                <td>' . $row['id'] . '</td>
                <td>' . $row['name'] . '</td>
                <td>' . $row['description'] . '</td>
                <td>$' . number_format($row['price'], 2) . '</td>
                <td>' . $row['stock'] . '</td>
                <td>' . $row['category_name'] . '</td>
                <td>
                    <button onclick="editProduct(' . $row['id'] . ')">Edit</button>
                    <button onclick="deleteProduct(' . $row['id'] . ')">Delete</button>
                </td>
            </tr>';
    }
} else {
    $output = '<tr><td colspan="7">No products found.</td></tr>';
}

echo $output;
?>

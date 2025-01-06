<?php
include 'config.php';

$id = $_GET['id'];

$stmt = $con->prepare("SELECT * FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$product_result = $stmt->get_result();
$product = $product_result->fetch_assoc();

$image_stmt = $con->prepare("SELECT * FROM imgs WHERE product_id = ?");
$image_stmt->bind_param("i", $id);
$image_stmt->execute();
$image_result = $image_stmt->get_result();
$images = [];
while ($image = $image_result->fetch_assoc()) {
    $images[] = $image['image_url'];
}

$response = [
    'product' => $product,
    'images' => $images,
];

echo json_encode($response);

$stmt->close();
$image_stmt->close();
?>

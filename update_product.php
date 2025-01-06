<?php
include 'config.php';

$product_name = $_POST['name'];
$product_description = $_POST['description'];
$product_price = $_POST['price'];
$product_stock = $_POST['stock'];
$product_category_id = $_POST['category_id'];

$stmt = $con->prepare("INSERT INTO products (name, description, price, stock, category_id) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("ssdii", $product_name, $product_description, $product_price, $product_stock, $product_category_id);

if ($stmt->execute()) {
    $product_id = $stmt->insert_id; 

    if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0) {
        $image_stmt = $con->prepare("INSERT INTO imgs (product_id, image_url) VALUES (?, ?)");
        
        for ($i = 0; $i < count($_FILES['images']['name']); $i++) {
            $image_url = 'imgs/' . $_FILES['images']['name'][$i]; 

            if (move_uploaded_file($_FILES['images']['tmp_name'][$i], $image_url)) {
                $image_stmt->bind_param("is", $product_id, $image_url);
                $image_stmt->execute();
            }
        }

        $image_stmt->close();
    }

    echo "Product and images added successfully!";
} else {
    echo "Error adding product: " . $con->error;
}

$stmt->close();
?>

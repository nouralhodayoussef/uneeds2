<?php
include 'config.php';

$product_id = $_POST['id'];
$product_name = $_POST['name'];
$product_description = $_POST['description'];
$product_price = $_POST['price'];
$product_stock = $_POST['stock'];
$product_category_id = $_POST['category_id'];

$check_query = $con->prepare("SELECT * FROM products WHERE id = ?");
$check_query->bind_param("i", $product_id);
$check_query->execute();
$result = $check_query->get_result();

if ($result->num_rows > 0) {
    $stmt = $con->prepare("UPDATE products SET name = ?, description = ?, price = ?, stock = ?, category_id = ? WHERE id = ?");
    $stmt->bind_param("ssdiii", $product_name, $product_description, $product_price, $product_stock, $product_category_id, $product_id);

    if ($stmt->execute()) {
        if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0) {
            $delete_images = $con->prepare("DELETE FROM imgs WHERE product_id = ?");
            $delete_images->bind_param("i", $product_id);
            $delete_images->execute();

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

        echo "Product updated successfully!";
    } else {
        echo "Error updating product: " . $con->error;
    }

    $stmt->close();
} else {
    echo "Product not found.";
}

$check_query->close();
?>

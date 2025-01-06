<?php
include_once 'config.php'; 
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category_id = $_POST['category_id'];

    $query = "INSERT INTO products (name, description, price, stock, category_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    $stmt->bind_param("ssdii", $name, $description, $price, $stock, $category_id);

    if ($stmt->execute()) {
        $product_id = $stmt->insert_id; 

        if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0) {
            $imageCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $imageCount; $i++) {
                $image_name = $_FILES['images']['name'][$i];
                $image_tmp = $_FILES['images']['tmp_name'][$i];
                $image_url = 'uploads/' . uniqid() . '_' . $image_name;

                if (move_uploaded_file($image_tmp, $image_url)) {
                    $img_query = "INSERT INTO imgs (product_id, image_url) VALUES (?, ?)";
                    $img_stmt = $con->prepare($img_query);
                    $img_stmt->bind_param("is", $product_id, $image_url);
                    $img_stmt->execute();
                }
            }
        }
        echo json_encode(['status' => 'success', 'message' => 'Product and images added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add product']);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

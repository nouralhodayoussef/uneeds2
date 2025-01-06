<?php
include_once 'config.php'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? null;
    $description = $_POST['description'] ?? null;
    $price = $_POST['price'] ?? null;
    $stock = $_POST['stock'] ?? null;
    $category_id = $_POST['category_id'] ?? null;

    if (empty($name) || empty($description) || empty($price) || empty($stock) || empty($category_id)) {
        echo json_encode(['status' => 'error', 'message' => 'All fields are required.']);
        exit;
    }

    $query = "INSERT INTO products (name, description, price, stock, category_id) VALUES (?, ?, ?, ?, ?)";
    $stmt = $con->prepare($query);
    
    if (!$stmt) {
        echo json_encode(['status' => 'error', 'message' => 'Prepare statement failed: ' . $con->error]);
        exit;
    }

    $stmt->bind_param("ssdii", $name, $description, $price, $stock, $category_id);

    if ($stmt->execute()) {
        $product_id = $stmt->insert_id; 

        if (isset($_FILES['images']) && count($_FILES['images']['name']) > 0) {
            $imageCount = count($_FILES['images']['name']);
            for ($i = 0; $i < $imageCount; $i++) {
                $image_name = $_FILES['images']['name'][$i];
                $image_tmp = $_FILES['images']['tmp_name'][$i];
                $image_url = 'imgs/' . uniqid() . '_' . $image_name;

                if (move_uploaded_file($image_tmp, $image_url)) {
                    $img_query = "INSERT INTO imgs (product_id, image_url) VALUES (?, ?)";
                    $img_stmt = $con->prepare($img_query);
                    
                    if (!$img_stmt) {
                        error_log("Image insert prepare failed: " . $con->error);
                        continue;
                    }

                    $img_stmt->bind_param("is", $product_id, $image_url);
                    if (!$img_stmt->execute()) {
                        error_log("Failed to insert image into database: " . $img_stmt->error);
                    }
                } else {
                    error_log("Failed to upload image: $image_name");
                }
            }
        }

        echo json_encode(['status' => 'success', 'message' => 'Product and images added successfully']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Failed to add product: ' . $stmt->error]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method']);
}
?>

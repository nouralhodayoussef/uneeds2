<?php
include 'config.php';

$id = isset($_POST['id']) ? $_POST['id'] : null;
$name = $_POST['name'];
$description = $_POST['description'];
$price = $_POST['price'];
$stock = $_POST['stock'];
$category_id = $_POST['category_id'];
$img = null;

if (isset($_FILES['img']) && $_FILES['img']['error'] == 0) {
    $target_dir = "imgs/";
    $image_name = uniqid() . "_" . basename($_FILES['img']['name']);
    $target_file = $target_dir . $image_name;

    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    if (in_array($_FILES['img']['type'], $allowed_types)) {
        if (move_uploaded_file($_FILES['img']['tmp_name'], $target_file)) {
            $img = $image_name;
        } else {
            echo "Error uploading the image.";
            exit;
        }
    } else {
        echo "Invalid image format.";
        exit;
    }
}

if ($id) {
    $sql = "
        UPDATE products 
        SET 
            name = '$name', 
            description = '$description', 
            price = '$price', 
            stock = '$stock', 
            category_id = '$category_id', 
            img = COALESCE('$img', img)
        WHERE id = '$id'";
} else {
    $sql = "
        INSERT INTO products (name, description, price, stock, category_id, img) 
        VALUES ('$name', '$description', '$price', '$stock', '$category_id', '$img')";
}

if ($con->query($sql) === TRUE) {
    echo "Product saved successfully.";
} else {
    echo "Database Error: " . $con->error;
}
?>

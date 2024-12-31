<?php
include 'config.php';

$id = $_POST['id'];
$stmt = $con->prepare("DELETE FROM products WHERE id = ?");
$stmt->bind_param("i", $id);
if ($stmt->execute()) {
    echo "Product deleted successfully.";
} else {
    echo "Error deleting product: " . $con->error;
}
$stmt->close();
?>

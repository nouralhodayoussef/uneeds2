<?php
include 'config.php';

$image_url = $_POST['image_url'];

$stmt = $con->prepare("DELETE FROM imgs WHERE image_url = ?");
$stmt->bind_param("s", $image_url);
if ($stmt->execute()) {
    echo "Image deleted successfully.";
} else {
    echo "Error deleting image: " . $con->error;
}
$stmt->close();
?>

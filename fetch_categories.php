<?php
include 'config.php';

$sql = "SELECT id, name FROM categories";
$result = $con->query($sql);

$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= '<option value="' . $row['id'] . '">' . $row['name'] . '</option>';
    }
}

echo $output;
?>
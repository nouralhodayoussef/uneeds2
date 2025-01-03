<?php
include 'config.php'; 

$sql = "
    SELECT 
        feedbacks.id AS feedback_id, 
        users.first_name AS user_name, 
        feedbacks.message 
    FROM feedbacks
    LEFT JOIN users ON feedbacks.user_id = users.id
";

$result = $con->query($sql);

$output = '';
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $output .= '
            <tr>
                <td>' . $row['feedback_id'] . '</td>
                <td>' . $row['user_name'] . '</td>
                <td>' . $row['message'] . '</td>
            </tr>';
    }
} else {
    $output = '<tr><td colspan="3">No feedback found.</td></tr>';
}

echo $output;
?>

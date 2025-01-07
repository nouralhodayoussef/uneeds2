<?php
session_start();
include 'config.php';

// Check if user is logged in
$userName = '';
if (isset($_SESSION['user_id'])) {
    $userId = $_SESSION['user_id'];
    $sql = "SELECT first_name FROM users WHERE id = ?";
    $stmt = mysqli_prepare($con, $sql);
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if ($row = mysqli_fetch_assoc($result)) {
        $userName = $row['first_name'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/header.css" />
    <title>Header | Uneeds</title>
</head>
<body>
    <header class="header" id="header">
        <a href="home.php">
            <img src="imgs/logo.png" alt="Uneeds Logo" />
            <h2>Uneeds</h2>
        </a>

        <ul class="navbar" id="navbar">
            <li><a href="products.php">Products</a></li>
            <li><a href="home.php#about-us">About Us</a></li>
            <li><a href="home.php#about-us">Contact Us</a></li>
        </ul>

        <div class="righthead">
            <i class="bx bx-menu menu-icon" id="menu-icon"></i>
            <a href="cart_style.php"><i class="bx bx-cart shop-cart"></i></a>
            <?php if (!empty($userName)): ?>
                <span class="welcome-msg">Hello, <?php echo htmlspecialchars($userName); ?></span>
                <a href="logout.php" class="btn">LOGOUT</a>
            <?php else: ?>
                <a href="login.php" class="btn">SIGN UP</a>
            <?php endif; ?>
        </div>
    </header>
    <script src="js/script.js"></script>
</body>
</html>

<?php
include 'config.php';
session_start();

$error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));

    if (empty($email)) {
        $error = "Email is required.";
    } elseif (empty($password)) {
        $error = "Password is required.";
    } else {
        $sql = "SELECT * FROM users WHERE email = ?";
        $stmt = mysqli_prepare($con, $sql);
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if ($row = mysqli_fetch_assoc($result)) {
            if (password_verify($password, $row['password'])) {
                if ($row['isAdmin'] == 1) {
                    header("Location: admin.php");
                } else {
                    header("Location: home.php");
                }
                exit();
            } else {
                $error = "Invalid password.";
            }
        } else {
            $error = "Invalid user.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/index.css">
    <link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <title>Sign In</title>
    <style>
        .error {
            color: red;
            font-size: 14px;
            visibility: hidden;
        }

        .error.visible {
            visibility: visible;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-box" id="login-form">
            <form action="" method="POST" name="Formfill">
                <div class="top">
                    <div>
                        <img class="icon" src="imgs/logo.png" alt="">
                    </div>
                    <div>
                        <h2>Sign In Now</h2>
                    </div>
                </div>
                <p class="error <?php echo !empty($error) ? 'visible' : ''; ?>"><?php echo $error; ?></p>
                <div class="input-box">
                    <i class='bx bxs-envelope'></i>
                    <input type="email" autocomplete="on" name="email" placeholder="Enter your email" required>
                </div>
                <div class="input-box">
                    <i class='bx bx-lock'></i>
                    <input type="password" name="password" placeholder="Enter your password" required>
                </div>
                <div class="button">
                    <input class="btn" type="submit" value="Sign In">
                </div>
                <div class="group">
                    <span>Do Not Have an account? <a href="register.html">Register</a></span>
                </div>
            </form>
        </div>
    </div>
</body>

</html>

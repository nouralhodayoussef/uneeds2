<?php
include 'config.php';
session_start();

$errors = [
    'fname' => '',
    'lname' => '',
    'email' => '',
    'password' => '',
    'cPassword' => '',
    'phonenumber' => '',
    'address' => '',
];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Sanitize user input
    $fname = htmlspecialchars(trim($_POST['fname']));
    $lname = htmlspecialchars(trim($_POST['lname']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = htmlspecialchars(trim($_POST['password']));
    $cPassword = htmlspecialchars(trim($_POST['cPassword']));
    $phonenumber = htmlspecialchars(trim($_POST['phonenumber']));
    $address = htmlspecialchars(trim($_POST['address']));

    // Validate inputs (one at a time)
    if (empty($fname)) {
        $errors['fname'] = "First name is required.";
    }
    else if (empty($lname)) {
        $errors['lname'] = "Last name is required.";
    }
    else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = "Invalid email format.";
    }
   else if (strlen($password) < 8 || strlen($password) > 18 ) {
        $errors['password'] = "Password must be 8-18 characters long";
    }
    else if(!preg_match('/[A-Z]/', $password) || !preg_match('/[@#$%_]/', $password)){
        $errors['password'] = "Include at least 1 uppercase letter and 1 special character";
    }
    else if ($password !== $cPassword) {
        $errors['cPassword'] = "Passwords do not match.";
    }
    else if (empty($phonenumber) || !preg_match('/^\d+$/', $phonenumber)) {
        $errors['phonenumber'] = "Phone number must contain only digits.";
    }
    else if (empty($address)) {
        $errors['address'] = "Address is required.";
    }

    // If there are no errors, proceed with registration
    if (!array_filter($errors)) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Insert data into the users table
        $sql = "INSERT INTO users (first_name, last_name, email, password, phone, address, isAdmin) 
                VALUES ('$fname', '$lname', '$email', '$hashedPassword', '$phonenumber', '$address', 0)";

        if (mysqli_query($con, $sql)) {
            header("Location: login.php");
            exit();
        } else {
            $errors['email'] = "Error: " . mysqli_error($con);
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
    <title>Sign Up</title>
    <style>
        .error {
            color: red;
            font-size: 14px;
            margin: 3px 5px;
            padding: 0;

            visibility: hidden; /* By default, errors are invisible */
        }

        .error.visible {
            visibility: visible; /* Only visible when error exists */
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="form-box">
            <form action="" method="POST" name="Formfill">

                <div class="top">
                    <div>
                        <img class="icon" src="imgs/logo.png" alt="">
                    </div>
                    <div>
                        <h2>Register Now</h2>
                    </div>
                </div>

                <div class="firstinput">
                    <div>
                        <input type="text" class="name-box" name="fname" placeholder="First Name" value="<?php echo htmlspecialchars($_POST['fname'] ?? ''); ?>" >
                        <p class="error <?php echo !empty($errors['fname']) ? 'visible' : ''; ?>"><?php echo $errors['fname']; ?></p>
                    </div>
                    <div>
                        <input type="text" class="name-box" name="lname" placeholder="Last Name" value="<?php echo htmlspecialchars($_POST['lname'] ?? ''); ?>" >
                        <p class="error <?php echo !empty($errors['lname']) ? 'visible' : ''; ?>"><?php echo $errors['lname']; ?></p>
                    </div>
                </div>

                <div class="input-box">
                    <i class='bx bxs-envelope'></i>
                    <input type="email" name="email" placeholder="Enter your email" value="<?php echo htmlspecialchars($_POST['email'] ?? ''); ?>" required>
                    <p class="error <?php echo !empty($errors['email']) ? 'visible' : ''; ?>"><?php echo $errors['email']; ?></p>
                </div>

                <div class="input-box">
                    <i class='bx bx-lock'></i>
                    <input type="password" name="password" placeholder="Enter your password" required>
                    <p class="error <?php echo !empty($errors['password']) ? 'visible' : ''; ?>"><?php echo $errors['password']; ?></p>
                </div>

                <div class="input-box">
                    <i class='bx bx-lock'></i>
                    <input type="password" name="cPassword" placeholder="Confirm Your password" required>
                    <p class="error <?php echo !empty($errors['cPassword']) ? 'visible' : ''; ?>"><?php echo $errors['cPassword']; ?></p>
                </div>

                <div class="input-box">
                    <i class='bx bx-phone'></i>
                    <input type="text" name="phonenumber" placeholder="Enter your number" value="<?php echo htmlspecialchars($_POST['phonenumber'] ?? ''); ?>" required>
                    <p class="error <?php echo !empty($errors['phonenumber']) ? 'visible' : ''; ?>"><?php echo $errors['phonenumber']; ?></p>
                </div>

                <div class="input-box">
                    <i class='bx bxs-location-plus'></i>
                    <input type="text" name="address" placeholder="Enter your address" value="<?php echo htmlspecialchars($_POST['address'] ?? ''); ?>" required>
                    <p class="error <?php echo !empty($errors['address']) ? 'visible' : ''; ?>"><?php echo $errors['address']; ?></p>
                </div>

                <div class="button">
                    <input type="submit" class="btn" value="Register">
                </div>
                <div class="group">
                    <span>Have an account? <a href="login.php">login</a></span>
                </div>

            </form>
        </div>
    </div>
</body>

</html>

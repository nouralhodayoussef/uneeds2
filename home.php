<?php
session_start();
include 'config.php';


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['feedback'])) {
    header('Content-Type: application/json');
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $message = trim($_POST['feedback']);

        if (!empty($message)) {
            $stmt = $con->prepare("INSERT INTO feedbacks (user_id, message) VALUES (?, ?)");
            if ($stmt) {
                $stmt->bind_param("is", $user_id, $message);

                if ($stmt->execute()) {
                    echo json_encode(['status' => 'success', 'message' => 'Thank you for your feedback!']);
                } else {
                    echo json_encode(['status' => 'error', 'message' => 'Error: Unable to submit feedback.']);
                }
                $stmt->close();
            } else {
                echo json_encode(['status' => 'error', 'message' => 'Database error: ' . $con->error]);
            }
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Please provide feedback before submitting.']);
        }
    } else {
        echo json_encode(['status' => 'error', 'message' => 'You must be logged in to submit feedback.']);
    }
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Uneeds</title>
    <link rel="icon" href="imgs/logo.png" type="image/png" sizes="16x16">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/home/container.css" />
    <link rel="stylesheet" href="css/home/slider.css" />
    <link rel="stylesheet" href="css/home/aboutus.css" />
    <script>
        document.addEventListener("DOMContentLoaded", () => {
            const feedbackForm = document.querySelector(".feedback-form");

            feedbackForm.addEventListener("submit", function (e) {
                e.preventDefault();

                const feedbackInput = document.querySelector(".feedback-input");
                const feedback = feedbackInput.value.trim();

                if (feedback) {
                    fetch("home.php", {
                        method: "POST",
                        headers: {
                            "Content-Type": "application/x-www-form-urlencoded",
                        },
                        body: `feedback=${encodeURIComponent(feedback)}`,
                    })
                        .then((response) => response.json())
                        .then((data) => {
                            if (data.status === "success") {
                                console.log(data.message);
                                alert(data.message);
                                feedbackInput.value = "";
                            } else {
                                console.error(data.message);
                                alert(data.message);
                            }
                        })
                        .catch((error) => console.error("Error:", error));
                } else {
                    console.error("Please provide feedback before submitting.");
                }
            });
        });
    </script>
</head>

<body>
    <div id="header"></div>

    <script>
        document.addEventListener("DOMContentLoaded", () => {
            fetch('header.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('header').innerHTML = data;
                    const menuToggle = document.querySelector('.menu-icon');
                    const navbar = document.querySelector('.navbar');
                    if (menuToggle && navbar) {
                        menuToggle.addEventListener('click', () => {
                            navbar.classList.toggle('active');
                        });
                    }
                })
                .catch(error => {
                    console.error('Error loading header: ', error);
                });
        });
    </script>

    <!-- Container -->

    <div class="container">
        <div class="containercontent">
            <div>
                <div class="statement" style="padding-top: 20px;">
                    <h4>WE COVER <span>ALL YOUR</span> UNEEDS</h4>
                </div>
                <div style="padding-right: 25px;">
                    <p>Your one-stop-shop for all university needs</p>
                </div>
                <a class="button" href="products.html">Show Now</a>
            </div>
            <div>
                <img src="imgs/3.png" alt="">
            </div>
        </div>
    </div>


    <!-- Slider -->
    <div class="sliderdiv">
        <div class="slider">
            <button class="slider-btn prev"><i class="bx bx-left-arrow-alt"></i></button>
            <div id="containerProduct" class="slider-track">
                <div id="box">

                    <a href="productDetails.html">
                        <img src="imgs/products/plan1.jpeg" alt="Notebook A4" />
                        <div id="details">
                            <h3>Pink Mug</h3>
                            <h4>Mugs</h4>

                        </div>
                    </a>
                </div>
                <div id="box">
                    <a href="productDetails.html">
                        <img src="imgs/products/plan1.jpeg" alt="Gel Pen - Blue" />
                        <div id="details">
                            <h3>Daily Planner</h3>
                            <h4>Planners</h4>

                        </div>
                    </a>
                </div>
                <div id="box">
                    <a href="productDetails.html">
                        <img src="imgs/products/plan1.jpeg" alt="Leather Bound Journal" />
                        <div id="details">
                            <h3>Leather Journal</h3>
                            <h4>Stationery</h4>

                        </div>
                    </a>
                </div>
                <div id="box">
                    <a href="productDetails.html">
                        <img src="imgs/products/plan1.jpeg" alt="" />
                        <div id="details">
                            <h3>Stitch Tumbler</h3>
                            <h4>Tumblers</h4>

                        </div>
                    </a>
                </div>

            </div>
            <button class="slider-btn next"><i class="bx bx-right-arrow-alt"></i></button>
        </div>
    </div>




    <!-- About us -->

    <div class="yellowDiv"></div>

    <section id="about-us">
        <div class="about-us">
            <div class="about-content">
                <h2>About Us</h2>
                <p>At <strong>Uneeds</strong>, we provide high-quality products at affordable prices, ensuring a
                    seamless
                    shopping experience. From home essentials to lifestyle accessories, we’re here to meet all your
                    needs with fast shipping and excellent customer service.</p>
                <form class="feedback-form">
                    <h3>Send us your feedback</h3>
                    <input type="text" name="feedback" placeholder="Your feedback here..." class="feedback-input"
                        required />
                    <button type="submit" class="submit-btn">Submit</button>
                </form>
            </div>
        </div>
    </section>

    <div id="footer"></div>

    <script>
        fetch('footer.php')
            .then(response => response.text())
            .then(data => document.getElementById('footer').innerHTML = data)
            .catch(error => console.error('Error loading footer: ', error));
    </script>

    <script src="js/script.js"></script>
</body>

</html>
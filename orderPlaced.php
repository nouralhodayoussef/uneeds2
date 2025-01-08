<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Uneeds</title>
    <link rel="icon" href="imgs/logo.png" type="image/png" sizes="16x16" />
    <link
      href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css"
      rel="stylesheet"
    />
    <link rel="stylesheet" href="css/orderPlaced.css" />
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

    <!-- ORDER PLACED -->
    <div id="orderContainer">
      <div id="aboutCheck">
        <h1>Order Placed Successfully!</h1>
        <p>Thank You for Your Purchase! You will receive your order very soon.</p>
      </div>
    </div>

    <div id="footer"></div>

    <script>
        fetch('footer.php')
            .then(response => response.text())
            .then(data => document.getElementById('footer').innerHTML = data)
            .catch(error => console.error('Error loading footer: ', error));
    </script>
    <script src="script.js"></script>
  </body>
</html>

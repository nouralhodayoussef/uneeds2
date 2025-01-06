<?php
include 'config.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Uneeds Admin</title>
    <link rel="icon" href="imgs/logo.png" type="image/png" sizes="16x16">
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="css/admin.css" />
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body>
    <div class="sidebar">
        <h2><span>Uneed's</span> Admin Panel</h2>
        <ul>
            <li id="products-tab" class="active">Products</li>
            <li id="orders-tab">Orders</li>
            <li id="feedback-tab">Feedback</li>
        </ul>
    </div>

    <div class="content">
        <!-- Products Section -->
        <div id="products-section" class="section active">
            <h2>Products</h2>
            <button id="add-product-button">Add Product</button>
            <table id="products-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Description</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Category</th>
                        <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <!-- Fetched rows will be inserted here -->
                </tbody>
            </table>

            <div id="product-form" class="form" style="display: none;">
                <h3 id="form-title">Add Product</h3>
                <label for="product-id">ID:</label>
                <input type="text" id="product-id" placeholder="Product ID" readonly>
                <label for="product-name">Name:</label>
                <input type="text" id="product-name" placeholder="Product Name">
                <label for="product-description">Description:</label>
                <textarea id="product-description" placeholder="Product Description"></textarea>
                <label for="product-price">Price:</label>
                <input type="text" id="product-price" placeholder="Product Price">
                <label for="product-stock">Stock:</label>
                <input type="number" id="product-stock" placeholder="Stock Quantity">
                <label for="product-category">Category:</label>
                <select id="product-category">
                    <!-- Categories fetched via AJAX -->
                </select>
                <label for="product-imgs">Images:</label>
                <input type="file" id="product-imgs" multiple>
                <button id="save-product-button">Save</button>
            </div>

        </div>

        <!-- Orders Section -->
        <div id="orders-section" class="section">
            <h2>Orders</h2>
            <table id="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Product</th>
                        <th>Total Price</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fetched rows will be inserted here -->
                </tbody>
            </table>
        </div>

        <!-- Feedback Section -->
        <div id="feedback-section" class="section">
            <h2>Feedback</h2>
            <table id="feedback-table">
                <thead>
                    <tr>
                        <th>Feedback ID</th>
                        <th>Customer</th>
                        <th>Message</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Fetched rows will be inserted here -->
                </tbody>
            </table>
        </div>
    </div>

    <script>
        const tabs = document.querySelectorAll('.sidebar ul li');
        const sections = document.querySelectorAll('.section');
        tabs.forEach((tab, index) => {
            tab.addEventListener('click', () => {
                tabs.forEach(t => t.classList.remove('active'));
                sections.forEach(s => s.classList.remove('active'));
                tab.classList.add('active');
                sections[index].classList.add('active');
            });
        });

        function fetchProducts() {
            $.ajax({
                url: 'fetch_products.php',
                type: 'GET',
                success: function (data) {
                    $('#products-table tbody').html(data);
                }
            });
        }

        function fetchOrders() {
            $.ajax({
                url: 'fetch_orders.php',
                type: 'GET',
                success: function (data) {
                    $('#orders-table tbody').html(data);
                }
            });
        }

        $('#add-product-button').click(function () {
            $('#form-title').text('Add Product');
            $('#product-id').val('');
            $('#product-name').val('');
            $('#product-price').val('');
            $('#product-description').val('');
            $('#product-stock').val('');
            $('#product-category').val('');
            $('#product-imgs').val('');
            $('#product-form').show();

            $('html, body').animate({
                scrollTop: $('#product-form').offset().top,
            }, 500);
        });

        $('#save-product-button').click(function () {
            const formData = new FormData();
            formData.append('name', $('#product-name').val());
            formData.append('description', $('#product-description').val());
            formData.append('price', $('#product-price').val());
            formData.append('stock', $('#product-stock').val());
            formData.append('category_id', $('#product-category').val());

            const files = $('#product-imgs')[0].files;
            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    formData.append('imgs[]', files[i]);
                }
            }

            $.ajax({
                url: 'save_product.php',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function (response) {
                    alert(response);
                    if (response.includes('success')) {
                        $('#product-form').hide();
                        fetchProducts();
                    }
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while saving the product.');
                },
            });
        });

        function fetchCategories() {
            $.ajax({
                url: 'fetch_categories.php',
                type: 'GET',
                success: function (data) {
                    $('#product-category').html(data);
                },
            });
        }

        $(document).ready(function () {
            fetchCategories();
        });

        $(document).ready(function () {
            fetchProducts();
            fetchOrders();
        });
    </script>
</body>

</html>

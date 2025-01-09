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

                <div id="product-images"></div>

                <label for="product-imgs">Images:</label>
                <input type="file" id="product-imgs" multiple>

                <button id="save-product-button">Save</button>
            </div>

        </div>

        <!-- Other sections -->
        <div id="orders-section" class="section">
            <h2>Orders</h2>
            <table id="orders-table">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>Products</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Orders will be dynamically populated here -->
                </tbody>
            </table>
        </div>



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
            formData.append('id', $('#product-id').val());
            formData.append('name', $('#product-name').val());
            formData.append('description', $('#product-description').val());
            formData.append('price', $('#product-price').val());
            formData.append('stock', $('#product-stock').val());
            formData.append('category_id', $('#product-category').val());

            const files = $('#product-imgs')[0].files;
            if (files.length > 0) {
                for (let i = 0; i < files.length; i++) {
                    formData.append('images[]', files[i]);
                }
            }

            $.ajax({
                url: 'update_product.php',
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
                }
            });
        });
        $(document).ready(function () {
            $('#orders-tab').click(function () {
                fetchOrders();
            });
        });

        $(document).on('change', '.order-status-select', function () {
            const orderId = $(this).data('order-id');
            const newStatus = $(this).val();

            $.ajax({
                url: 'update_order_status.php',
                type: 'POST',
                data: { order_id: orderId, new_status: newStatus },
                success: function (response) {
                    alert(response);
                },
                error: function (xhr, status, error) {
                    console.error('Error updating order status:', error);
                    alert('An error occurred while updating the order status.');
                }
            });
        });



        function fetchOrders() {
            $.ajax({
                url: 'fetch_orders.php',
                type: 'GET',
                success: function (data) {
                    $('#orders-table tbody').html(data);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching orders:', error);
                    alert('An error occurred while fetching orders.');
                }
            });
        }

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
            fetchProducts();
            $('#feedback-tab').click(function () {
                fetchFeedback();
            });
        });

        $(document).on('click', '.delete-image', function () {
            const imagePath = $(this).data('image');
            const productId = $('#product-id').val();

            if (confirm('Are you sure you want to delete this image?')) {
                $.ajax({
                    url: 'delete_product_image.php',
                    type: 'POST',
                    data: { product_id: productId, image_url: imagePath },
                    success: function (response) {
                        alert(response);
                        editProduct(productId);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the image.');
                    }
                });
            }
        });


        function editProduct(productId) {
            $.ajax({
                url: 'fetch_single_product.php',
                type: 'GET',
                data: { id: productId },
                success: function (response) {
                    const data = JSON.parse(response);

                    if (data.error) {
                        alert(data.error);
                        return;
                    }

                    $('#form-title').text('Edit Product');
                    $('#product-id').val(data.product.id);
                    $('#product-name').val(data.product.name);
                    $('#product-description').val(data.product.description);
                    $('#product-price').val(data.product.price);
                    $('#product-stock').val(data.product.stock);
                    $('#product-category').val(data.product.category_id);

                    $('#product-images').html('');
                    data.images.forEach(function (imageUrl) {
                        $('#product-images').append(`
                    <div class="image-container">
                        <img src="${imageUrl}" alt="Product Image" style="max-width: 100px; max-height: 100px;">
                        <button class="delete-image" data-url="${imageUrl}">Delete</button>
                    </div>
                `);
                    });

                    $('#product-form').show();

                    $('html, body').animate({
                        scrollTop: $('#product-form').offset().top,
                    }, 500);
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while fetching product details.');
                }
            });
        }

        function deleteProduct(productId) {
            $.ajax({
                url: 'delete_product.php',
                type: 'POST',
                data: { id: productId },
                success: function (response) {
                    alert(response);
                    fetchProducts();
                },
                error: function (xhr, status, error) {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the product.');
                }
            });
        }


        $(document).on('click', '.delete-image', function () {
            const imagePath = $(this).data('image');
            const productId = $('#product-id').val();

            if (confirm('Are you sure you want to delete this image?')) {
                $.ajax({
                    url: 'delete_product_image.php',
                    type: 'POST',
                    data: { product_id: productId, image_url: imagePath },
                    success: function (response) {
                        alert(response);
                        editProduct(productId);
                    },
                    error: function (xhr, status, error) {
                        console.error('Error:', error);
                        alert('An error occurred while deleting the image.');
                    }
                });
            }
        });
        function fetchFeedback() {
            $.ajax({
                url: 'fetch_feedback.php',
                type: 'GET',
                success: function (data) {
                    $('#feedback-table tbody').html(data);
                },
                error: function (xhr, status, error) {
                    console.error('Error fetching feedback:', error);
                    alert('An error occurred while fetching feedback.');
                }
            });
        }

    </script>
</body>

</html>
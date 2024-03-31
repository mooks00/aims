<?php
require 'database.php'; // Include the database connection file

// Function to fetch all products from the database
function getProducts() {
    global $conn;
    $sql = "SELECT * FROM product";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $products = $result->fetch_all(MYSQLI_ASSOC);
        return $products;
    } else {
        return [];
    }
}

// Function to add a new product to the database
function addProduct($product_name, $description, $avail_qty, $unit_price, $category_id) {
    global $conn;

    // Retrieve the category ID based on the selected category
    // Replace 'category_table' with the actual name of your category table
    $category_sql = "SELECT category_id FROM category_table WHERE category_id = $category_id";
    $category_result = $conn->query($category_sql);

    if ($category_result->num_rows > 0) {
        // Category ID exists, insert the product into the database
        $sql = "INSERT INTO product (Product_name, Description, avail_Qty, Unit_price, category_id) VALUES ('$product_name', '$description', $avail_qty, $unit_price, $category_id)";
        $conn->query($sql);
        return $conn->insert_id;
    } else {
        // Category ID does not exist, handle the error
        // You can redirect the user to an error page or display an error message
        echo "Invalid category ID";
    }
}

// Function to remove a product from the database
function removeProduct($product_id) {
    global $conn;
    $sql = "DELETE FROM product WHERE product_id = $product_id";
    $conn->query($sql);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is for adding a product
    if (isset($_POST['add_product'])) {
        $product_name = $_POST['product_name'];
        $description = $_POST['description'];
        $avail_qty = $_POST['avail_qty'];
        $unit_price = $_POST['unit_price'];
        $category_id = $_POST['category_id'];

        addProduct($product_name, $description, $avail_qty, $unit_price, $category_id);
    }

    // Check if the form is for removing a product
    if (isset($_POST['remove_product'])) {
        $product_id = $_POST['product_id'];
        removeProduct($product_id);
    }
}

// Fetch all products from the database
$products = getProducts();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Products</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white" style=" box-shadow: 0 2px 4px 0 rgba(0,0,0,.2)">
    <a class="navbar-brand" href="dashboard.php">
    <img src="logo.png" class="logo" style="padding: 5px 8px"/>
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="products.php">Products</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="categories.php">Categories</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="vendor.php">Vendor</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="sales.php">Sales</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="orders.php">Orders</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="shipments.php">Shipments</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="logout.php">Log Out</a>
                </li>
            </ul>
        </div>
    </nav>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>


    <div class="container">
        <h1>Products</h1>

        <div class="row">
            <div class="col-md-6">
                <h2>Add Product</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="product_name">Product Name:</label>
                        <input type="text" class="form-control" id="product_name" name="product_name" required>
                    </div>

                    <div class="form-group">
                        <label for="description">Description:</label>
                        <textarea class="form-control" id="description" name="description" required></textarea>
                    </div>

                    <div class="form-group">
                        <label for="avail_qty">Available Quantity:</label>
                        <input type="number" class="form-control" id="avail_qty" name="avail_qty" required>
                    </div>

                    <div class="form-group">
                        <label for="unit_price">Unit Price:</label>
                        <input type="number" class="form-control" id="unit_price" name="unit_price" step="0.01" required>
                    </div>

                    <button type="submit" class="btn btn-primary" name="add_product">Add Product</button>
                </form>
            </div>

            <div class="col-md-6">
                <h2>Product List</h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Product ID</th>
                            <th>Product Name</th>
                            <th>Description</th>
                            <th>Available Quantity</th>
                            <th>Unit Price</th>
                            <th>Category ID</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($products as $product): ?>
                            <tr>
                                <td><?php echo $product['product_id']; ?></td>
                                <td><?php echo $product['Product_name']; ?></td>
                                <td><?php echo $product['Description']; ?></td>
                                <td><?php echo $product['avail_Qty']; ?></td>
                                <td><?php echo $product['Unit_price']; ?></td>
                                <td><?php echo $product['category_id']; ?></td>
                                <td>
                                    <form method="POST">
                                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                                        <button type="submit" class="btn btn-danger" name="remove_product">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.5.4/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
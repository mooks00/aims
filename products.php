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
function addProduct($product_name, $description, $avail_qty, $unit_price) {
    global $conn;
    $sql = "INSERT INTO product (Product_name, Description, avail_Qty, Unit_price) VALUES ('$product_name', '$description', $avail_qty, $unit_price)";
    $conn->query($sql);
    return $conn->insert_id;
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

        addProduct($product_name, $description, $avail_qty, $unit_price);
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
    <?php include 'include/styles.html'; ?>
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

                    <button type="submit" class="btn btn-primary" name="add_product" style="margin-top:20px; background-color:#006A4E">Add Product</button>
                </form>
            </div>

            <div class="col-md-6">
    <h2>Product List</h2>
    <?php if (count($products) > 0): ?>
        <ul class="list-group">
            <?php foreach ($products as $product): ?>
                <li class="list-group-item">
                    <strong><?php echo $product['product_name']; ?></strong><br>
                    Quantity: <?php echo $product['avail_qty']; ?><br>
                    Price: <?php echo $product['unit_price']; ?><br>
                    Description: <?php echo $product['description']; ?><br>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="product_id" value="<?php echo $product['product_id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm" name="remove_product" style="margin-top:10px">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No products found.</p>
    <?php endif; ?>
</div>
        </div>
    </div>
</body>
</html>
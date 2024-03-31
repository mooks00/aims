<?php
require 'database.php'; // Include the database connection file

// Function to fetch all orders from the database
function getOrders() {
    global $conn;
    $sql = "SELECT orders.order_id, orders.product_id, product.product_name, orders.order_qty, product.unit_price, orders.order_qty * product.unit_price AS order_price, orders.order_date
            FROM orders
            INNER JOIN product ON orders.product_id = product.product_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $orders = $result->fetch_all(MYSQLI_ASSOC);
        return $orders;
    } else {
        return [];
    }
}

// Function to add a new order to the database
function addOrder($productId, $orderQty) {
    global $conn;
    
    // Fetch the unit price of the product
    $sql = "SELECT unit_price FROM product WHERE product_id = $productId";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $unitPrice = $row['unit_price'];
        $orderPrice = $orderQty * $unitPrice;

        // Insert the new order with the current date and time
        $sql = "INSERT INTO orders (product_id, order_qty, order_price, order_date) VALUES ($productId, $orderQty, $orderPrice, NOW())";
        $conn->query($sql);

        return $conn->insert_id;
    } else {
        return false;
    }
}

// Function to remove an order from the database// Function to remove an order from the database
function removeOrder($orderId) {
    global $conn;
    
    // Delete related shipments first
    $sql = "DELETE FROM shipments WHERE order_id = $orderId";
    $conn->query($sql);
    
    // Delete the order
    $sql = "DELETE FROM orders WHERE order_id = $orderId";
    $conn->query($sql);
}

// Fetch all orders from the database
$orders = getOrders();

// Fetch products for dropdown
$products = [];
$sql = "SELECT * FROM product";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productId = $_POST['product_id'];
    $orderQty = $_POST['order_qty'];

    // Add order to the database
    $orderId = addOrder($productId, $orderQty);

    if ($orderId) {
        $orderMessage = "Order added successfully!";
    } else {
        $orderMessage = "Failed to add order.";
    }
}

// Handle order removal
if (isset($_GET['remove_order'])) {
    $orderId = $_GET['remove_order'];

    // Remove order from the database
    removeOrder($orderId);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
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
        <h1>Orders</h1>

        <div class="row">
            <div class="col-md-6">
                <h2>Add Order</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="product_id">Product:</label>
                        <select class="form-control"name="product_id" id="product_id" required>
                            <option value="">Select a product..</option>
                            <?php foreach ($products as $product): ?>
                                <option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="order_qty">Quantity:</label>
                        <input type="number" class="form-control" name="order_qty" id="order_qty" placeholder="Enter quantity.." required>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top:20px; background-color:#006A4E">Add Order</button>
                </form>
                <?php if (isset($orderMessage)): ?>
                    <p class="text-success"><?php echo $orderMessage; ?></p>
                <?php endif; ?>
            </div>
        </div>

        <h2>Order List</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product ID</th>
                    <th>Product Name</th>
                    <th>Quantity</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                    <th>Order Date</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($orders as $order): ?>
                    <tr>
                        <td><?php echo $order['order_id']; ?></td>
                        <td><?php echo $order['product_id']; ?></td>
                        <td><?php echo $order['product_name']; ?></td>
                        <td><?php echo $order['order_qty']; ?></td>
                        <td><?php echo $order['unit_price']; ?></td>
                        <td><?php echo $order['order_price']; ?></td>
                        <td><?php echo $order['order_date']; ?></td>
                        <td>
                            <a href="?remove_order=<?php echo $order['order_id']; ?>" class="btn btn-danger btn-sm">Remove</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
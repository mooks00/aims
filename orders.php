<?php
require 'database.php'; // Include the database connection file

// Function to fetch all orders from the database
function getOrders() {
    global $conn;
    $sql = "SELECT orders.order_id, orders.sales_id, sales.Order_date, orders.product_id, product.product_name, orders.order_qty, orders.order_price
            FROM orders
            INNER JOIN sales ON orders.sales_id = sales.sales_id
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
function addOrder($salesId, $productId, $orderQty, $orderPrice) {
    global $conn;
    $sql = "INSERT INTO orders (sales_id, product_id, order_qty, order_price) VALUES ($salesId, $productId, $orderQty, $orderPrice)";
    $conn->query($sql);
    return $conn->insert_id;
}

// Function to remove an order from the database
function removeOrder($orderId) {
    global $conn;
    $sql = "DELETE FROM orders WHERE order_id = $orderId";
    $conn->query($sql);
}

// Fetch all orders from the database
$orders = getOrders();

// Fetch sales for dropdown
$sales = [];
$sql = "SELECT * FROM sales";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $sales = $result->fetch_all(MYSQLI_ASSOC);
}

// Fetch products for dropdown
$products = [];
$sql = "SELECT * FROM product";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $products = $result->fetch_all(MYSQLI_ASSOC);
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Orders</title>
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
                        <label for="sales_id">Sales:</label>
                        <select class="form-control" id="sales_id" name="sales_id" required>
                            <?php foreach ($sales as $sale): ?>
                                <option value="<?php echo $sale['sales_id']; ?>"><?php echo $sale['sales_id']; ?> - <?php echo $sale['Order_date']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="product_id">Product:</label>
                        <select class="form-control" id="product_id" name="product_id" required>
                            <?php foreach ($products as $product): ?>
                                <option value="<?php echo $product['product_id']; ?>"><?php echo $product['product_id']; ?> - <?php echo $product['product_name']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="order_qty">Order Quantity:</label>
                        <input type="number" class="form-control" id="order_qty" name="order_qty" required>
                    </div>

                    <div class="form-group">
                        <label for="order_price">Order Price:</label>
                        <input type="number" class="form-control" id="order_price" name="order_price" step="0.01" required>
                    </div>

                    <button type="submit" class="btn btn-primary" name="add_order">Add Order</button>
                </form>
            </div>

            <div class="col-md-6">
                <h2>Order List</h2>
                <?php if (count($orders) > 0): ?>
                    <ul class="list-group">
                        <?php foreach ($orders as $order): ?>
                            <li class="list-group-item">
                                Order ID: <?php echo $order['order_id']; ?> - Sales ID: <?php echo $order['sales_id']; ?> - Order Date: <?php echo $order['Order_date']; ?> - Product ID: <?php echo $order['product_id']; ?> - Product Name: <?php echo $order['product_name']; ?> - Quantity: <?php echo $order['order_qty']; ?> - Price: $<?php echo $order['order_price']; ?>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p>No orders found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
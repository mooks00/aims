<?php
require 'database.php'; 

function getSales() {
    global $conn;
    $sql = "SELECT sales_id, order_date, total_amount FROM sales";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $sales = $result->fetch_all(MYSQLI_ASSOC);
        return $sales;
    } else {
        return [];
    }
}

function addSale($orderDate) {
    global $conn;

    $sql = "SELECT SUM(order_price) AS total_amount FROM orders WHERE order_date = '$orderDate'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();
    $totalAmount = $row['total_amount'];

    $sql = "INSERT INTO sales (order_date, total_amount) VALUES ('$orderDate', '$totalAmount')";
    $conn->query($sql);
    return $conn->insert_id;
}

function removeSale($saleId) {
    global $conn;
    $saleId = $conn->real_escape_string($saleId); 
    $sql = "DELETE FROM sales WHERE sales_id = '$saleId'";
    $conn->query($sql);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
   
    if (isset($_POST['add_sale'])) {
        $orderDate = $_POST['order_date'];

        addSale($orderDate);
    }

    if (isset($_POST['remove_sale'])) {
        if (isset($_POST['sale_id'])) {
            $saleId = $_POST['sale_id'];
            removeSale($saleId);
        }
    }
}

$sales = getSales();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales</title>
    <?php include 'include/styles.html'; ?>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-light bg-white" style="box-shadow: 0 2px 4px 0 rgba(0,0,0,.2)">
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
    <h1>Sales</h1>

    <div class="row">
        <div class="col-md-6">
            <h2>Add Sale</h2>
            <form method="POST">
                <div class="form-group">
                    <label for="order_date">Order Date:</label>
                    <input type="date" class="form-control" id="order_date" name="order_date" required>
                </div>
                <button type="submit" class="btn btn-primary" name="add_sale" style="margin-top:20px; background-color:#006A4E">Add Sale</button>
            </form>
        </div>
        <div class="col-md-6">
    <h2>Sale List</h2>
    <?php if (!empty($sales)): ?>
        <ul class="list-group">
            <?php foreach ($sales as $sale): ?>
                <li class="list-group-item">
                    <strong>Order Date:</strong> <?php echo $sale['order_date']; ?><br>
                    <?php
                        $orderDate = $sale['order_date'];
                        $sql = "SELECT SUM(order_price) AS total_amount FROM orders WHERE order_date = '$orderDate'";
                        $result = $conn->query($sql);
                        $row = $result->fetch_assoc();
                        $totalAmount = $row['total_amount'];
                    ?>
                    <strong>Total Amount:</strong> <?php echo $totalAmount; ?><br>
                    <form method="POST" style="display: inline;">
                        <input type="hidden" name="sale_id" value="<?php echo $sale['sales_id']; ?>">
                        <button type="submit" class="btn btn-danger btn-sm" name="remove_sale" onclick="return confirm('Are you sure you want to remove this sale?')">Remove</button>
                    </form>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>No sales found.</p>
    <?php endif; ?>
</div>
    </div>
</div>

</body>
</html>
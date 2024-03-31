<?php
require 'database.php'; // Include the database connection file

// Function to fetch all sales from the database
function getSales() {
    global $conn;
    $sql = "SELECT * FROM sales";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $sales = $result->fetch_all(MYSQLI_ASSOC);
        return $sales;
    } else {
        return [];
    }
}

// Function to add a new sale to the database
function addSale($orderDate, $totalAmount, $status) {
    global $conn;
    $sql = "INSERT INTO sales (Order_date, Total_amount, Status) VALUES ('$orderDate', $totalAmount, '$status')";
    $conn->query($sql);
    return $conn->insert_id;
}

// Function to remove a sale from the database
function removeSale($saleId) {
    global $conn;
    $sql = "DELETE FROM sales WHERE sales_id = $saleId";
    $conn->query($sql);
}

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if the form is for adding a sale
    if (isset($_POST['add_sale'])) {
        $orderDate = $_POST['order_date'];
        $totalAmount = $_POST['total_amount'];
        $status = $_POST['status'];

        addSale($orderDate, $totalAmount, $status);
    }

    // Check if the form is for removing a sale
    if (isset($_POST['remove_sale'])) {
        $saleId = $_POST['sale_id'];
        removeSale($saleId);
    }
}

// Fetch all sales from the database
$sales = getSales();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Sales</title>
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
        <h1>Sales</h1>

        <div class="row">
            <div class="col-md-6">
                <h2>Add Sale</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="order_date">Order Date:</label>
                        <input type="date" class="form-control" id="order_date" name="order_date" required>
                    </div>

                    <div class="form-group">
                        <label for="total_amount">Total Amount:</label>
                        <input type="number" class="form-control" id="total_amount" name="total_amount" step="0.01" required>
                    </div>

                    <div class="form-group">
                        <label for="status">Status:</label>
                        <select class="form-control" id="status" name="status" required>
                            <option value="pending">Pending</option>
                            <option value="approved">Approved</option>
                            <option value="shipped">Shipped</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" name="add_sale">Add Sale</button>
                </form>
            </div>

            <div class="col-md-6">
                <h2>Sale List</h2>
                <?php if (count($sales) > 0): ?>
                    <ul class="list-group">
                        <?php foreach ($sales as $sale): ?>
                            <li class="list-group-item">
                                <?php echo $sale['Order_date']; ?> - $<?php echo $sale['Total_amount']; ?> - <?php echo $sale['Status']; ?>
                                <form method="POST" style="display: inline;">
                                    <input type="hidden" name="sale_id" value="<?php echo $sale['sales_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" name="remove_sale">Remove</button>
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
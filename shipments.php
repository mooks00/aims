<?php
require 'database.php'; // Include the database connection file

// Function to fetch all shipments from the database
function getShipments() {
    global $conn;
    $sql = "SELECT shipments.shipment_id, shipments.order_id, shipments.shipment_date, shipments.tracking_num, shipments.delivery_status
            FROM shipments
            INNER JOIN orders ON shipments.order_id = orders.order_id";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $shipments = $result->fetch_all(MYSQLI_ASSOC);
        return $shipments;
    } else {
        return [];
    }
}

// Function to add a new shipment to the database
function addShipment($orderId, $shipmentDate, $trackingNum, $deliveryStatus) {
    global $conn;
    $sql = "INSERT INTO shipments (order_id, shipment_date, tracking_num, delivery_status) VALUES ($orderId, '$shipmentDate', '$trackingNum', '$deliveryStatus')";
    $conn->query($sql);
    return $conn->insert_id;
}

// Function to remove a shipment from the database
function removeShipment($shipmentId) {
    global $conn;
    $sql = "DELETE FROM shipments WHERE shipment_id = $shipmentId";
    $conn->query($sql);
}

// Fetch all shipments from the database
$shipments = getShipments();

// Fetch orders for dropdown
$orders = [];
$sql = "SELECT order_id FROM orders";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $orders = $result->fetch_all(MYSQLI_ASSOC);
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_shipment'])) {
        $orderId = $_POST['order_id'];
        $shipmentDate = $_POST['shipment_date'];
        $trackingNum = $_POST['tracking_num'];
        $deliveryStatus = $_POST['delivery_status'];

        addShipment($orderId, $shipmentDate, $trackingNum, $deliveryStatus);
        header('Location: shipments.php'); // Redirect after adding the shipment
        exit();
    } elseif (isset($_POST['remove_shipment'])) {
        $shipmentId = $_POST['shipment_id'];

        removeShipment($shipmentId);
        header('Location: shipments.php'); // Redirect after removing the shipment
        exit();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Shipments</title>
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
        <h1>Shipments</h1>

        <div class="row">
            <div class="col-md-6">
                <h2>Add Shipment</h2>
                <form method="POST">
                    <div class="form-group">
                        <label for="order_id">Order:</label>
                        <select class="form-control" id="order_id" name="order_id" required>
                            <?php foreach ($orders as $order): ?>
                                <option value="<?php echo $order['order_id']; ?>"><?php echo $order['order_id']; ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="shipment_date">Shipment Date:</label>
                        <input type="date" class="form-control" id="shipment_date" name="shipment_date" required>
                    </div>

                    <div class="form-group">
                        <label for="tracking_num">Tracking Number:</label>
                        <input type="text" class="form-control" id="tracking_num" name="tracking_num" maxlength="12" required>
                    </div>

                    <div class="form-group">
                        <label for="delivery_status">Delivery Status:</label>
                        <select class="form-control" id="delivery_status" name="delivery_status" required>
                            <option value="To pack">To Pack</option>
                            <option value="Packed">Packed</option>
                            <option value="In transit">In Transit</option>
                            <option value="Delivered">Delivered</option>
                        </select>
                    </div>

                    <button type="submit" class="btn btn-primary" name="add_shipment" style="margin-top:20px; background-color:#006A4E">Add Shipment </button>
                </form>
            </div>

            <div class="col-md-6">
                <h2>Shipment List</h2>
                <?php if (count($shipments) > 0): ?>
                <table class="table">
                    <thead>
                        <tr>
                            <th>Shipment ID</th>
                            <th>Order ID</th>
                            <th>Shipment Date</th>
                            <th>Tracking Number</th>
                            <th>Delivery Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($shipments as $shipment): ?>
                            <tr>
                                <td><?php echo $shipment['shipment_id']; ?></td>
                                <td><?php echo $shipment['order_id']; ?></td>
                                <td><?php echo $shipment['shipment_date']; ?></td>
                                <td><?php echo $shipment['tracking_num']; ?></td>
                                <td><?php echo $shipment['delivery_status']; ?></td>
                                <td>
                                    <form method="POST" onsubmit="return confirm('Are you sure you want to remove this shipment?');">
                                        <input type="hidden" name="shipment_id" value="<?php echo $shipment['shipment_id']; ?>">
                                        <button type="submit" class="btn btn-danger" name="remove_shipment" style="margin-top:10px">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php else: ?>
                    <p>No shipments found.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</body>
</html>
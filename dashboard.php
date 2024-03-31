<?php
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

// Get current date
date_default_timezone_set('Asia/Manila');
$currentDate = date("Y-m-d");
?>


<!DOCTYPE html>
<html>
<head>
    <title>Dashboard</title>
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

    <div style="background-color:#006A4E">
    <div class="container d-flex justify-content-center align-items-center" style="height: 500px">
        <div class="row">
            <div class="col text-center">
                <h1 style="color: white">Welcome, <?php echo $_SESSION['username']; ?>!</h1>
                <p style="color: white"><i>Today's Date: <?php echo $currentDate; ?></i></p>
            </div>
        </div>
    </div>

    <!-- Optional additional content -->
    <div class="content">

    </div>

</div>
</body>
</html>
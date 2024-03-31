<?php
$host = 'localhost'; // Database host, usually localhost
$db_user = 'root'; // Database username
$db_password = ''; // Database password
$db_name = 'aimsystem'; // Database name

$conn = new mysqli($host, $db_user, $db_password, $db_name);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
else{
    //$conn->close();
}
?>
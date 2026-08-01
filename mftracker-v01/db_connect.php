<?php
$servername = "sql306.infinityfree.com";
$username = "if0_42471116";
$password = "XdXuynBVqW";
$dbname = "if0_42471116_mftracker";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>

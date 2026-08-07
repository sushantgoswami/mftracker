<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

include 'db_connect.php';

$username = $_SESSION['username'];

$purchase_total_value = 0;
$current_total_value = 0;
    
// $stmt = $conn->prepare("SELECT * FROM `" . $_SESSION['tablename'] . "`);
// $stmt->execute();
// $result = $stmt->get_result();

$sql = "SELECT * FROM `" . $_SESSION['tablename'] . "`";
$result = $conn->query($sql);
 
$current_initial_value = 0;
$purchase_initial_value = 0;
$units_initial_value = 0;
$gainloss_initial_value = 0;
$currentnav_initial_value = 0;

while ($row = $result->fetch_assoc())
	{
    $current_initial_value = $current_initial_value + $row['Current_Value']; 
    $purchase_initial_value = $purchase_initial_value + $row['Purchase_Value'];      
    }
$_SESSION['current_total_value'] = $current_initial_value;
$_SESSION['purchase_total_value'] = $purchase_initial_value;
// $stmt->close();
$conn->close();

header("Location: index.php");
exit();
?>
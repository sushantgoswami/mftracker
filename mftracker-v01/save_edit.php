<?php

include 'db_connect.php';
  
session_start();

$id = $_SESSION['id'];
$fundname = $_SESSION['fundname'];
$date = $_SESSION['date'];
$purchasenav = $_POST['purchasenav'];
$units = $_POST['units'];
$isincode = $_SESSION['isincode'];

	$stmt = $conn->prepare("UPDATE `" . $_SESSION['tablename'] . "` SET Purchase_NAV = ?, Units = ? WHERE id = ?");
	$stmt->bind_param("iii", $purchasenav, $units, $id);
	$stmt->execute();
	$result = $stmt->get_result();

    if ($stmt->execute()) {
        $msg = "Data saved successfully.";
    } else {
        $msg = "Error: ";
    }
echo $purchasenav;
echo $units;
echo $id;
echo $msg;
    $stmt->close();
    $conn->close();

$extra="index.php";
$host=$_SERVER['HTTP_HOST'];
$uri=rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
header("location:http://$host$uri/$extra");
exit();

?>
<?php

include 'db_connect.php';
  
session_start();

$fundname = $_SESSION['fundname'];
$date = $_SESSION['date'];
$purchasenav = $_SESSION['purchasenav'];
$units = $_SESSION['units'];
$isincode = $_SESSION['isincode'];
$currentnav = "0";

	$stmt = $conn->prepare("SELECT ISIN_Code FROM `" . $_SESSION['tablename'] . "` WHERE Fund_Name = ?");
	$stmt->bind_param("s", $fundname);
	$stmt->execute();

	$result = $stmt->get_result();

	if ($row = $result->fetch_assoc()) {
    $isincode = $row["ISIN_Code"];
	}

    $stmt = $conn->prepare("INSERT INTO `" . $_SESSION['tablename'] . "` (ISIN_Code, Fund_Name, Date, Current_NAV, Purchase_NAV, Units) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $isincode, $fundname, $date, $currentnav, $purchasenav, $units);

    if ($stmt->execute()) {
        $msg = "Data saved successfully.";
    } else {
        $msg = "Error: ";
    }

    $stmt->close();
    $conn->close();

// session_unset();
$extra="index.php";
$host=$_SERVER['HTTP_HOST'];
$uri=rtrim(dirname($_SERVER['PHP_SELF']),'/\\');
header("location:http://$host$uri/$extra");
exit();

?>

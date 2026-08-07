<?php

include '../db_connect.php';
  
session_start();
if (!isset($_SESSION['username'])) { header("Location: ../../index.php"); exit; }

$fundname = $_POST['fundname'];
$date = $_POST['date'];
$purchasenav = $_POST['purchasenav'];
$units = $_POST['units'];
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
        $_SESSION['msg']  = "Data saved successfully.";
    } else {
        $_SESSION['msg'] = "Error saving data: ";
    }

    $stmt->close();
    $conn->close();

header("location: ../calculate.php");
exit();

?>
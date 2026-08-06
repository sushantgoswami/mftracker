<?php

include '../db_connect.php';
  
session_start();

$fundname = $_SESSION['fundname'];
$date = $_SESSION['date'];
$purchasenav = $_SESSION['purchasenav'];
$units = $_SESSION['units'];
$isincode = $_SESSION['isincode'];
$currentnav = "0";

    $stmt = $conn->prepare("INSERT INTO `" . $_SESSION['tablename'] . "` (ISIN_Code, Fund_Name, Date, Current_NAV, Purchase_NAV, Units) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $isincode, $fundname, $date, $currentnav, $purchasenav, $units);

    if ($stmt->execute()) {
        $msg = "Data saved successfully.";
    } else {
        $msg = "Error: ";
    }

    $stmt->close();
    $conn->close();

header("location: ../index.php");
exit();

?>

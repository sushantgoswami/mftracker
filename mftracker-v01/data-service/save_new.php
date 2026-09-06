<?php

include '../db_connect.php';
  
session_start();
if (!isset($_SESSION['username'])) { header("Location: ../../index.php"); exit; }

$fundname = $_SESSION['fundname'];
$date = $_SESSION['date'];
$purchasenav = $_SESSION['purchasenav'];
$units = $_SESSION['units'];
$isincode = $_SESSION['isincode'];
$currentnav = "0";

        // parse NAV data
        $filePath = "../cache/nav/{$isincode}.nav.txt";
        $currentnav = null;
        if (file_exists($filePath)) {
                        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        if (!empty($lines)) {
                        $lastLine = end($lines);
                        $fields = str_getcsv($lastLine, ';');
                        $currentnav = $fields[count($fields) - 2];
                        }
        } else {
                        $lines = file("../cache/amfinav/NAVAll.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        foreach ($lines as $line) {
                        if (strpos($line, $isincode) !== false) {
                        $fields = str_getcsv($line, ';');
                        $currentnav = $fields[count($fields) - 2];
                        break;
                        }
                        }
        }
        // parse NAV data end

    $stmt = $conn->prepare("INSERT INTO `" . $_SESSION['tablename'] . "` (ISIN_Code, Fund_Name, Date, Current_NAV, Purchase_NAV, Units) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $isincode, $fundname, $date, $currentnav, $purchasenav, $units);

    if ($stmt->execute()) {
        $msg = "Data saved successfully.";
    } else {
        $msg = "Error: ";
    }

    $stmt->close();
    $conn->close();

header("location: ../calculate.php");
exit();

?>

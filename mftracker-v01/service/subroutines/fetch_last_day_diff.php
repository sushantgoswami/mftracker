<?php

include '../../db_connect.php';

session_start();

if (!isset($_SESSION['username'])) { header("Location: ../../../index.php"); exit; }

$tablename = $_SESSION['tablename'];

$isincode = $_SESSION['isincode'];

$sql = "SELECT DISTINCT ISIN_Code FROM `" . $_SESSION['tablename'] . "` ORDER BY ISIN_Code";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {

        $isincode = $row['ISIN_Code'];

        // parse NAV data
        $filePath = "../../cache/nav/{$isincode}.nav.txt";
        $currentnav1 = null;
	$currentnav2 = null;
	$diff = 0;
        if (file_exists($filePath)) {
                        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        if (!empty($lines)) {
                        $lastLine = end($lines);
                        $fields = str_getcsv($lastLine, ';');
                        $currentnav1 = $fields[count($fields) - 2];
			if (count($lines) >= 2) {
    				$secondLastLine = $lines[count($lines) - 2];
				$fields = str_getcsv($secondLastLine, ';');
				$currentnav2 = $fields[count($fields) - 2];
				if ($currentnav2) {
					$diff = $currentnav1 - $currentnav2;
				}
			} else {
				$currentnav2 = 0;
				$diff = 0;
			}
                        }
	}
        // parse NAV data end
}

// $conn->close();
// exit;
?>

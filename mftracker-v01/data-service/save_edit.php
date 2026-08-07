<?php

include '../db_connect.php';
  
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: ../../login.php");
    exit;
}

$id = $_SESSION['id'];
$fundname = $_SESSION['fundname'];
$date = $_SESSION['date'];
$purchasenav = $_POST['purchasenav'];
$units = $_POST['units'];
$isincode = $_SESSION['isincode'];

	$stmt = $conn->prepare("UPDATE `" . $_SESSION['tablename'] . "` SET Purchase_NAV = ?, Units = ? WHERE id = ?");
	$stmt->bind_param("ddi", $purchasenav, $units, $id);
	$result = $stmt->get_result();

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Data saved successfully.";
    } else {
        $_SESSION['msg'] = "Error: Saving data.";
    }
echo $purchasenav;
echo $units;
echo $id;
echo $msg;
    $stmt->close();
    $conn->close();

header("location: ../calculate.php");
exit();

?>
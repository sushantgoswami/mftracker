<?php
    
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

include '../db_connect.php';

if (isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("DELETE FROM `" . $_SESSION['tablename'] . "` WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: ../calculate.php"); // Change to your page name
        $_SESSION['msg'] = "Record deleted.";
        exit;
    } else {
        $_SESSION['msg'] = "Error deleting record.";
    }

    $stmt->close();
}

$conn->close();
?>

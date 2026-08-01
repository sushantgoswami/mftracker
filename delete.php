<?php
include 'db_connect.php';

if (isset($_GET['id'])) {

    $id = (int)$_GET['id'];

    $stmt = $conn->prepare("DELETE FROM sushantgoswami01 WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php"); // Change to your page name
        exit;
    } else {
        echo "Error deleting record.";
    }

    $stmt->close();
}

$conn->close();
?>

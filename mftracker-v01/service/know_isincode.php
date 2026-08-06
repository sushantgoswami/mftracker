<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>mftracker isincode</title>
    <link rel="icon" type="image/x-icon" href="images/favicon.ico">
    <link rel="stylesheet" href="../css/style5.css">
</head>
<body>
<table style="border:2px;">
<div class="container mt-4">
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../../index.php");
    exit;
}
// $file = "../NAVAll.txt";

foreach (file("../NAVAll.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $fields = explode(";", $line);

    if (isset($fields[1], $fields[3])) {
        echo "<tr>";
        echo "<td>".$fields[1]."</td>"; echo "<td>".$fields[3]."</td>";
        echo "<tr>";
    }
}
?>
</table>
</div>
</body>
</html>  
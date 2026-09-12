<?php

include '../db_connect.php';

$stmt1 = $conn->prepare("SELECT Fund_Name FROM `" . $_SESSION['tablename'] . "` WHERE ISIN_Code = ? LIMIT 1");
$stmt1->bind_param("s", $id);   // i = integer, s = string
$stmt1->execute();
$result1 = $stmt1->get_result();
while ($row1 = $result1->fetch_assoc()) {
    $fundname_user = $row1['Fund_Name'];
}
$stmt1->close();
echo $fundname_user;
?>


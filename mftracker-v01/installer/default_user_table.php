<?php
session_start();  
include '../db_connect.php';

// 5. Construct the SQL query with backticks around the variable
$sql1 = "CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `tablename` varchar(50) NOT NULL,
  `question1` varchar(35) NOT NULL,
  `answer1` varchar(15) NOT NULL,
  `last_invested_value` float(12,2) NOT NULL DEFAULT 0.00,
  `purchase_total_value` float(12,2) NOT NULL DEFAULT 0.00,
  `last_total_value` float(12,2) NOT NULL DEFAULT 0.00,
  `current_total_value` float(12,2) NOT NULL DEFAULT 0.00
)";
$stmt1 = $conn->prepare($sql1);
$stmt1->execute();
$stmt1->close();

$sql2 = "ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `username_2` (`username`)";
$stmt1 = $conn->prepare($sql2);
$stmt1->execute();
$stmt1->close();

$sql3 = "ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT";
$stmt1 = $conn->prepare($sql3);
$stmt1->execute();
$stmt1->close();


// if ($stmt1->execute()) {
//     $_SESSION['msg'] = "Registration Successful, Data Table Creation Successful";
// } else {
//     $_SESSION['msg'] = "Registration Successful, Data Table Creation is not Successful";
// }

// 7. Close connections
$conn->close();
// $stmt1->close();
header("Location: ../../index.php");
exit;
?>

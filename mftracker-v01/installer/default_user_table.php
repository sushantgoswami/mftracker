<?php
session_start();  
include '../db_connect.php';

// 5. Construct the SQL query with backticks around the variable
$sql = "CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(50) NOT NULL,
  `password` varchar(255) NOT NULL,
  `fullname` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `tablename` varchar(50) NOT NULL,
  `question1` varchar(35) NOT NULL,
  `answer1` varchar(15) NOT NULL
)";

// 6. Prepare and execute the query using the statement object
$stmt1 = $conn->prepare($sql);

if ($stmt1->execute()) {
    $_SESSION['msg'] = "Registration Successful, Data Table Creation Successful";
} else {
    $_SESSION['msg'] = "Registration Successful, Data Table Creation is not Successful";
}

// 7. Close connections
$conn->close();
$stmt1->close();
header("Location: ../../index.php");
exit;
?>
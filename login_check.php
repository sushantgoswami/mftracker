<?php
session_start();
include "mftracker-v01/db_connect.php";

$username = $_POST['username'];
$password = $_POST['password'];


$stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    if (password_verify($password, $row['password'])) {
        $_SESSION['username'] = $username;

        $fullname = $row['fullname'];
		$email = $row['email'];
        $tablename = $row['tablename'];
		$_SESSION['fullname']=$fullname;
		$_SESSION['email']=$email;
        $_SESSION['tablename']=$tablename;
        $stmt->close();
		$conn->close();
        header("Location: mftracker-v01/index.php");
        exit;
    }
}
$_SESSION['failure'] = "Username or Password Incorrect.!";
header("Location: login.php");
exit;
?>

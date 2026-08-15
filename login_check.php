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
        if ($username == "administrator") {
         header("Location: mftracker-v01/admin/admin_index.php");  
         exit();
        } else {
        // Clear and readable log message format
		 $logFile = "mftracker-v01/log/login_check.log";
		 $logMessage = "[" . date("d-m-Y H:i:s") . "] " . basename($_SERVER['PHP_SELF']) . " (MSG:0001): file executed successfully. $username logged in." . PHP_EOL;
		 $_SESSION['logMessage'] = $logMessage;
		 $_SESSION['logFile'] = $logFile;
		 include 'mftracker-v01/log/logger.php';
		 unset($_SESSION['logMessage']);
		 unset($_SESSION['logFile']);
		 // Clear and readable log message format
         header("Location: mftracker-v01/calculate.php");
         exit();
        }
    }
}
$_SESSION['msg'] = "Username or Password Incorrect.!";
header("Location: index.php");
exit;
?>

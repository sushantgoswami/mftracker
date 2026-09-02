<?php
session_start();

$username = $_SESSION['username'];

// Clear and readable log message format
 $logFile = "mftracker-v01/log/logout.log";
 $logMessage = "[" . date("d-m-Y H:i:s") . "] " . basename($_SERVER['PHP_SELF']) . " (MSG:0001): file executed successfully. $username logged out." . PHP_EOL;
 file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);
session_destroy();

header("Location: login.php");
exit;
?>

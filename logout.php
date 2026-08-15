<?php
session_start();

$username = $_SESSION['username'];

// Clear and readable log message format
 $logFile = "mftracker-v01/log/logout.log";
 $logMessage = "[" . date("d-m-Y H:i:s") . "] " . basename($_SERVER['PHP_SELF']) . " (MSG:0001): file executed successfully. $username logged out." . PHP_EOL;
 $_SESSION['logMessage'] = $logMessage;
 $_SESSION['logFile'] = $logFile;
 include 'mftracker-v01/log/logger.php';
// Clear and readable log message format

session_destroy();

header("Location: login.php");
exit;
?>

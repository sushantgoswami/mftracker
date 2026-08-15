<?php
    
session_start();

// if (!isset($_SESSION['username'])) { header("Location: ../../index.php"); exit; }

$logMessage = $_SESSION['logMessage'];
$logFile = $_SESSION['logFile'];

file_put_contents($logFile, $logMessage, FILE_APPEND | LOCK_EX);

unset($_SESSION['logMessage']);
unset($_SESSION['logFile']);

?>
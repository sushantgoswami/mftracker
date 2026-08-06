<?php
    
session_start();
if (!isset($_SESSION['username'])) { header("Location: ../../index.php"); exit; }

$username = $_SESSION['username'];

$file = 'user_login.txt';
$messagecode = "[MSG001] ";
{$currentDateTime} = date('d-m-Y H:i:s');
$data = " --- User logged IN.\n";

$data = '{$messagecode} {$currentDateTime} {$data}';

// Use FILE_APPEND to preserve existing data. 
// LOCK_EX prevents others from writing to the file at the same time.
file_put_contents($file, $data, FILE_APPEND | LOCK_EX);
?>
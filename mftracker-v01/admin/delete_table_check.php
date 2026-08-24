<?php
    
session_start();

if (!isset($_SESSION['username'])) { header("Location: ../login.php"); exit; }

include "../db_connect.php";

$username = $_SESSION['username'];

if ($username == 'administrator') {
$userid = $_POST['userid'];
$tablename_user = $_POST['tablename_user'];
$captcha_verify = $_POST['Verify'];
$captcha = $_SESSION['Captcha'];

if ($captcha_verify != $captcha) {
 $_SESSION['msg'] = "Captcha code mismatch";
 header("Location: admin_index.php"); 
 exit;
}

if (isset($_POST['tabledelete'])) {
   $quotedTable = '`' . str_replace('`', '``', $tablename_user) . '`';
   $sql = "DROP TABLE IF EXISTS $quotedTable";
   if ($conn->query($sql)) {
   	$_SESSION['msg'] = "table deletion Successful";
   	header("Location: admin_index.php");
   	exit;
   } else {
       $_SESSION['msg'] = "table deletion not Successful"; 
   	header("Location: admin_index.php");
   	exit;
   }  
}
$conn->close();
} else {
$_SESSION['msg'] = "Only administrator can delete usernames and tables.";
header("Location: ../../index.php");
exit;
}
?>
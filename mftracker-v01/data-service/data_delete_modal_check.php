<?php

session_start();

if (!isset($_SESSION['username'])) { header("Location: ../login.php"); exit; }

include "../db_connect.php";

$userid = $_POST['userid'];
$tablename_user = $_POST['tablename_user'];
$isincode_user = $_POST['isincode_user'];
$captcha_verify = $_POST['Verify'];
$captcha = $_SESSION['Captcha'];

if ($captcha_verify != $captcha) {
 $_SESSION['msg'] = "Captcha code mismatch";
 header("Location: admin_index.php");
 exit;
}

if (isset($_POST['funddelete'])) {
    $sql = "DELETE FROM `" . $tablename_user . "` WHERE ISIN_Code = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $isincode_user);
   if ($stmt->execute()) {
        $_SESSION['msg'] = "fund deletion Successful";
        header("Location: ../index.php");
        exit;
   } else {
       $_SESSION['msg'] = "fund deletion not Successful";
        header("Location: ../index.php");
        exit;
   }
}
$conn->close();

?>

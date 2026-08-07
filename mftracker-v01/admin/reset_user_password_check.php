<?php
session_start();
include "../db_connect.php";

$username = $_POST['username'];
$password1 = $_POST['password1'];
$password2 = $_POST['password2'];
$captcha_verify = $_POST['Verify'];
$captcha = $_SESSION['Captcha'];

if ($password1 != $password2) {
 $_SESSION['msg'] = "New Password and Confirm Password do not match";
 header("Location: index.php"); 
 exit; }
else {
 $password = password_hash($_POST['password1'], PASSWORD_DEFAULT);
}

if ($captcha_verify != $captcha) {
 $_SESSION['msg'] = "Captcha code mismatch";
 header("Location: index.php"); 
 exit;
}
    $sql = "UPDATE users SET password = ? WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $password, $username);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Password reset Successful";
        header("Location: admin_index.php");
        exit;
    } else {
        $_SESSION['msg'] = "Password reset not Successful.";
        header("Location: admin_index.php");
        exit;
    }

$stmt->close();
$conn->close();
?>
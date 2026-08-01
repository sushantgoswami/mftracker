<?php
// Powered by Site.pro
// include dirname(__FILE__).'/mftracker-v01/index.php';
// echo "Site is Live"
?>
<?php
session_start();
unset($_SESSION['username']);
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}
?>

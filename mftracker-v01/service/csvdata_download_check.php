<?php    
session_start();
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="../css/style5.css">
    <title>Mutual Fund Records</title>
    <link rel="icon" type="image/x-icon" href="../icons/golden-indian-rupee.ico">
</head>
<body style="background-color:#e6ffe6;"> 
<div class='menu'>
<p style="padding-left:20%;"><font color=#00cc00><font size="4">Please Copy the below data in notepad and save. </font></font></p>
<p style="padding-left:20%;"><font color=#00cc00><font size="4">Return to Previous Page <a href="csvdata_download.php">Click Here</a></font></font></p>
</div

<?php
    
session_start();
include '../db_connect.php';

if (!isset($_SESSION['username'])) { header("Location: ../noindex.php"); exit; }
if (isset($_SESSION['msg'])) { echo "<script> alert('" . addslashes($_SESSION['msg']) . "'); </script>"; unset($_SESSION['msg']); }

$username = $_SESSION['username'];
$fundname = $_POST['fundname'];
$captcha_verify = $_POST['Verify'];
$captcha = $_SESSION['Captcha'];
    
if ($captcha_verify != $captcha) {
 $_SESSION['msg'] = "Captcha code mismatch";
 header("Location: csvdata_download.php"); 
 exit;
}

if ($fundname == 'allfunds') {
 $sql = "SELECT * FROM `" . $_SESSION['tablename'] . "` ORDER BY Fund_Name, Date";
 $stmt = $conn->prepare($sql);
 $stmt->execute();
 $result = $stmt->get_result();
 while ($row = $result->fetch_assoc()) {
  		echo "<tr>";
        echo "<td>".$row['ISIN_Code']."</td>"; echo ",";
     	echo "<td>".$row['Date']."</td>"; echo ",";
        echo "<td>".$row['Fund_Name']."</td>"; echo ",";
        echo "<td>".$row['Purchase_NAV']."</td>"; echo ",";
        echo "<td>".$row['Units']."</td>";
        echo "</tr>";
        ?><br><?
 }
 $stmt->close();
}

if ($fundname != 'allfunds') {
 $stmt = $conn->prepare("SELECT * FROM `" . $_SESSION['tablename'] . "` WHERE Fund_Name = ? ORDER BY Date");
 $stmt->bind_param("s", $fundname);
 $stmt->execute();
 $result = $stmt->get_result();
 while ($row = $result->fetch_assoc()) {
  		echo "<tr>";
        echo "<td>".$row['ISIN_Code']."</td>"; echo ",";
     	echo "<td>".$row['Date']."</td>"; echo ",";
        echo "<td>".$row['Fund_Name']."</td>"; echo ",";
        echo "<td>".$row['Purchase_NAV']."</td>"; echo ",";
        echo "<td>".$row['Units']."</td>";
        echo "</tr>";
        ?><br><?
 }  
 $stmt->close();
}
        
$conn->close();
// $_SESSION['msg'] = $outputlog;
?>
</body>
</html>
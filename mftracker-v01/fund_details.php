<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}
include 'db_connect.php';

$id = (string) $_GET['id'];

$stmt = $conn->prepare("SELECT * FROM `" . $_SESSION['tablename'] . "` WHERE ISIN_Code = ? ORDER BY Date");
$stmt->bind_param("s", $id);   // i = integer, s = string
$stmt->execute();
$result = $stmt->get_result();

$stmt1 = $conn->prepare("SELECT Fund_Name FROM `" . $_SESSION['tablename'] . "` WHERE ISIN_Code = ? LIMIT 1");
$stmt1->bind_param("s", $id);   // i = integer, s = string
$stmt1->execute();
$result1 = $stmt1->get_result();
while ($row1 = $result1->fetch_assoc()) { 
    $fund = $row1['Fund_Name']; 
}

?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="css/style5.css">
    <title>Mutual Fund Records</title>
    <link rel="icon" type="image/x-icon" href="icons/golden-indian-rupee.ico">
</head>
<body>

<h2>Fund Name - <? echo $fund; ?></h2>

<table>
    <tr>
        <th>Fund Name</th>
        <th>Date</th>
        <th>Current NAV</th>
        <th>Purchase NAV</th>
        <th>Units</th>
        <th>Purchase Value</th>
        <th>Current Value</th>
		<th>Gain Loss</th>
	    <th>Percentage</th>
        <th>Action</th>
    </tr>

<?php
    while ($row = $result->fetch_assoc()) 
	{
		echo "<tr>";
        echo "<td class='fund-name'>".$row['Fund_Name']."</td>";
        echo "<td>" . date('d-m-Y', strtotime($row['Date'])) . "</td>";
        echo "<td>".$row['Current_NAV']."</td>";
        echo "<td>".$row['Purchase_NAV']."</td>";
        echo "<td>".$row['Units']."</td>";
        echo "<td>".$row['Purchase_Value']."</td>";
        echo "<td>".$row['Current_Value']."</td>";
        $class = ($row['Gain_Loss'] >= 0) ? "profit" : "loss";
		echo "<td class='$class'>".$row['Gain_Loss']."</td>";
        $class = ($row['Percentage'] >= 0) ? "profit" : "loss";
        echo "<td class='$class'>".number_format($row['Percentage'], 2)." %</td>";
        echo "<td>
        <a href='data-service/edit_fund.php?id=".$row['id']."'
		onclick=\"return confirm('Edit this record?');\">
		<button style='background:#FFA500;color:white;border:none;padding:3px 6px;border-radius:5px;'>
		Edit
		</button>
		</a>
		<a href='data-service/delete_fund.php?id=".$row['id']."'
		onclick=\"return confirm('Delete this record?');\">
		<button style='background:#dc3545;color:white;border:none;padding:3px 6px;border-radius:5px;'>
		Delete
		</button>
		</a>
		</td>";
        echo "</tr>";
    }
$stmt->close();
$stmt1->close();
$conn->close();
?>
</table>
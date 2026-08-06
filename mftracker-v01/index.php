<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="css/style5.css">
    <link rel="icon" type="image/x-icon" href="icons/golden-indian-rupee.ico">
    <style>
    .parent-container-div {
	  display: flex;         /* Activates flexbox alignment */
	  gap: 20px;             /* Controls the exact space between the boxes */
	}

	.child-box {
	  flex: 1;               /* Makes both boxes take up equal width */
	  background-color: #eeffcc; /* Visual styling only */
	  padding: 10px;         /* Visual styling only */
	}
    </style>
</head>
    
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}
if (isset($_SESSION['msg'])) { echo "<script> alert('" . addslashes($_SESSION['msg']) . "'); </script>"; unset($_SESSION['msg']); }

// require_once 'service/update_nav_file.php';

include 'db_connect.php';

$username = $_SESSION['username'];
$fullname = $_SESSION['fullname'];
// $fullname = $_SESSION['fullname'];
$purchase_total_value = $_SESSION['purchase_total_value'];
$current_total_value = $_SESSION['current_total_value'];
$gainloss_total_value = $_SESSION['gainloss_total_value'];
$gainloss_percent_total_value = $_SESSION['gainloss_percent_total_value'];
?>
<head>
<h2>Mutual Fund Details - <?php echo $fullname; ?></h2>
</head>
<div class="menu parent-container-div">
    <div class="child-box">
    <p>➜ Enter Recent Purchases <a href="data-service/data_entry.php">Click Here</a></p>
    <p>➜ Add New Mutual Fund <a href="data-service/data_entry_new.php">Click Here</a></p>
    <p>➜ Update Latest NAV from AMFI <a href="download_update_nav.php">Click Here</a></p>
    <p>➜ Reset Password <a href="service/reset_password.php">Click Here</a></p>
    <p>➜ Logout <a href="../logout.php">Click Here</a></p>
    </div>
    <div class="child-box">
    <p>➜ Download fund data in CSV <a href="service/csvdata_download.php" target="_blank">Click Here</a></p>
    <p>➜ Upload data in CSV format data <a href="service/csvdata_upload.php" target="_blank">Click Here</a></p>   
    <p>➜ Download fund data in SQL <a href="service/sqldata_download.php">Click Here</a></p>     
    <p>➜ Upload data in SQL format data <a href="service/update_nav_file.php">Click Here</a></p>
    </div>
    <div class="child-box">
    <table>
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Total invested Value: </p></th><th><p><?php echo $purchase_total_value; ?></p></th></tr>
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Current invested Value: </p></th><th><p><?php echo $current_total_value; ?></p></th></tr>
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Gain Loss Value: </p></th><p><? $class = ($gainloss_total_value >= 0) ? "profit" : "loss"; echo "<td class='$class'>".$gainloss_total_value."</td>"; ?></p></tr>
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Gain Loss Percent Value: </p></th><p><? $class = ($gainloss_percent_total_value >= 0) ? "profit" : "loss"; echo "<td class='$class'>".number_format($gainloss_percent_total_value, 2)." %</td>"; ?></p></tr>
    </table>
    
    <button type="button" onclick="window.location.reload();">Refresh Page</button>
    </div>
</div>

<?php

$purchase_total_value = 0;
$current_total_value = 0;

// Get unique fund names
$sql = "SELECT DISTINCT Fund_Name FROM `" . $_SESSION['tablename'] . "` ORDER BY Fund_Name";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {

    $fund = $row['Fund_Name'];

    // Query for each unique fund
    $sql2 = "SELECT * FROM `" . $_SESSION['tablename'] . "` WHERE Fund_Name='$fund' ORDER BY Date";
    $result2 = $conn->query($sql2);
?>

<!DOCTYPE html>
<html>
<head>
    <title>mftracker</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="css/style5.css">
    <style>
    body {
      background-color: #e6e6ff;
    }
    .table-container {
    width: 100%;
    overflow-x: auto; /* Adds horizontal scrollbar if table overflows */
    border: 0px solid #ccc; /* Optional border for the box visual */
    padding: 0px;
    }
    </style>
</head>
<body>

<div class="table-container container">

<table>
    <tr>
        <th style="padding-right: 22ch; text-align: center;">Fund_Name</th>
        <th>ISIN_Code</th>
        <th>Date</th>
        <th>Current_NAV</th>
        <th>Units</th>
        <th>Purchase Value</th>
        <th>Current Value</th>
		<th>Gain Loss</th>
	    <th>Percentage</th>
        <th>Action</th>
    </tr>

<?php
    $currentDate = date('d-m-Y');
    $current_initial_value = 0;
    $purchase_initial_value = 0;
    $units_initial_value = 0;
    $gainloss_initial_value = 0;
    $currentnav_initial_value = 0;
    while ($row2 = $result2->fetch_assoc()) 
	{
        $current_initial_value = $current_initial_value + $row2['Current_Value']; 
        $purchase_initial_value = $purchase_initial_value + $row2['Purchase_Value'];
        $units_initial_value = $units_initial_value + $row2['Units'];
        $gainloss_initial_value = $gainloss_initial_value + $row2['Gain_Loss'];
        $currentnav_initial_value = $row2['Current_NAV'];
        $fundname = $row2['Fund_Name'];
        $isincode = $row2['ISIN_Code'];
        $current_total_value = $current_total_value + $row2['Purchase_Value'];
        $purchase_total_value = $purchase_total_value + $row2['Current_Value'];        
    }
    $percentage_value = ($gainloss_initial_value / $purchase_initial_value) * 100;
    echo "<hr>";
    	echo "<tr>";
        echo "<td><strong>".$fundname."</strong></td>";
        echo "<td><b>".$isincode."</b></td>";
        echo "<td><b>".$currentDate."</b></td>";
        echo "<td><b>".round($currentnav_initial_value, 2)."</b></td>";
        echo "<td><b>".round($units_initial_value, 2)."</b></td>";
        echo "<td><b>".round($purchase_initial_value, 2)."</b></td>";
        echo "<td><b>".round($current_initial_value, 2)."</b></td>";
        $class = ($row2['Gain_Loss'] >= 0) ? "profit" : "loss";
		echo "<td class='$class'>".$gainloss_initial_value."</td>";
        $class = ($row2['Percentage'] >= 0) ? "profit" : "loss";
        echo "<td class='$class'>".number_format($percentage_value, 2)." %</td>";
		?>
    	<td>
        <button class="btn btn-primary viewBtn"
                data-id="<?= $isincode; ?>">
            Details
        </button>
    	</td>
        <?
        echo "<tr>";
}
$conn->close();
?>
</table>

</div>
<? 
$gainloss_total_value = $purchase_total_value - $current_total_value;
$gainloss_percent_total_value = ($gainloss_total_value / $purchase_total_value) * 100;
$_SESSION['purchase_total_value'] = $purchase_total_value;
$_SESSION['current_total_value'] = $current_total_value;
$_SESSION['gainloss_total_value'] = $gainloss_total_value;
$_SESSION['gainloss_percent_total_value'] = $gainloss_percent_total_value;
?>
    
<!-- Modal -->
<div class="modal fade" id="orderModal">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">

            <div class="modal-header">
                <h5 class="modal-title">Fund Details</h5>

                <button class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>

            <div class="modal-body" id="modalBody">

                Loading...

            </div>

        </div>
    </div>
</div>

<script>

$(document).on("click",".viewBtn",function(){

    var id=$(this).data("id");

    $("#modalBody").html("Loading...");

    $("#modalBody").load("fund_details.php?id="+id);

    $("#orderModal").modal("show");

});

</script>

</body>
</html>

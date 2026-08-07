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
$gainloss_total_value = 0;
$gainloss_percent_total_value = 0;
$purchase_total_value = $_SESSION['purchase_total_value'];
$current_total_value = $_SESSION['current_total_value'];
if ($purchase_total_value > 0) {
$gainloss_total_value = $current_total_value - $purchase_total_value;
$gainloss_percent_total_value = ($gainloss_total_value / $purchase_total_value) * 100; }
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
    <p>➜ Upload data in SQL format data <a href="service/sqldata_upload.php">Click Here</a></p>
    </div>
    <div class="child-box">
    <table>
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Current invested Value: </p></th><th><p><?php echo $current_total_value; ?></p></th></tr>
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Total invested Value: </p></th><th><p><?php echo $purchase_total_value; ?></p></th></tr>
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Gain Loss Value: </p></th><p><? $class = ($gainloss_total_value >= 0) ? "profit" : "loss"; echo "<td class='$class'>".$gainloss_total_value."</td>"; ?></p></tr>
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Gain Loss Percent Value: </p></th><p><? $class = ($gainloss_percent_total_value >= 0) ? "profit" : "loss"; echo "<td class='$class'>".number_format($gainloss_percent_total_value, 2)." %</td>"; ?></p></tr>
    </table>    
    <button type="button" onclick="window.location.reload();">Refresh Values</button>
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
    <link href="bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/jquery-3.7.1.min.js"></script>
    <script src="bootstrap/bootstrap.bundle.min.js"></script>
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
	/* Dark semi-transparent background over the whole screen */
	.modal-overlay {
	  position: fixed;
	  top: 0;
	  left: 0;
	  width: 100%;
	  height: 100%;
	  background-color: rgba(0, 0, 0, 0.4); /* 50% transparent black */
	  display: flex;
	  justify-content: center;
	  align-items: center;
	  z-index: 1000;
	}	
	/* White semi-transparent container box */
	.modal-box {
	  background-color: rgba(255, 255, 255, 0.95); /* 85% transparent white */
	  padding: 20px;
	  border-radius: 8px;
	  color: #333333; /* Text remains 100% solid */
	  box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
	}
    .modal-close-btn {
    /* Button container sizing */
	  width: 40px;
      height: 40px;
      padding: 10px;
      background-color: orange;
    }
    </style>
</head>
<body>

<div class="container table-container">

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
        $current_total_value = $current_total_value + $row2['Current_Value'];
        $purchase_total_value = $purchase_total_value + $row2['Purchase_Value'];        
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
        $class = ($gainloss_initial_value >= 0) ? "profit" : "loss";
		echo "<td class='$class'>".$gainloss_initial_value."</td>";
        $class = ($percentage_value >= 0) ? "profit" : "loss";
        echo "<td class='$class'>".number_format($percentage_value, 2)." %</td>";
		?>
    	<td>
        <button class="btn btn-primary viewBtn1" data-toggle="modal" data-target="#Modal1"
                data-id="<?= $isincode; ?>">
            Details
        </button>
        <button style="background-color:orange" class="btn btn-primary viewBtn2" data-toggle="modal" data-target="#Modal2"
                data-id="<?= $isincode; ?>">
            Add
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
if ($purchase_total_value > 0) {
$gainloss_total_value = $current_total_value - $purchase_total_value; 
$gainloss_percent_total_value = ($gainloss_total_value / $purchase_total_value) * 100; }
$_SESSION['purchase_total_value'] = $purchase_total_value;
$_SESSION['current_total_value'] = $current_total_value;
?>
    
<!-- Modal -->
<div class="modal fade" id="Modal1">
    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fund Details</h5>
                <button class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body" id="modalBody1">
                Loading...
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="Modal2">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-overlay">
            <div class="modal-box modal-close-btn modal-header">                
                <button class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body" id="modalBody2">
                Loading...
            </div>
        </div>
    </div>
</div>
    
<script>

$(document).on("click",".viewBtn1",function(){

    var id=$(this).data("id");
    $("#modalBody1").html("Loading...");
    $("#modalBody1").load("fund_details.php?id="+id);
    $("#Modal1").modal("show");

});

</script>
<script>

$(document).on("click",".viewBtn2",function(){

    var id=$(this).data("id");
    $("#modalBody2").html("Loading...");
    $("#modalBody2").load("data-service/data_entry_modal.php?id="+id);
    $("#Modal2").modal("show");

});

</script>

</body>
</html>

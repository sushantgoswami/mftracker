<!DOCTYPE html>
<head>
    <link href="bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="bootstrap/jquery-3.7.1.min.js"></script>
    <script src="bootstrap/bootstrap.bundle.min.js"></script>
    <script src="charts/js/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
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
    	.table-container {
    	width: 100%;
    	overflow-x: auto; /* Adds horizontal scrollbar if table overflows */
    	border: 0px solid #ccc; /* Optional border for the box visual */
    	padding: 0px;
    	}
        .titlebar {
            height: 55px;
            background: #1f2937;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 20px;
            box-shadow: 0 2px 5px rgba(0,0,0,0.2);
        }

        .title {
            font-size: 20px;
            font-weight: bold;
        }

        .logout {
            background: #dc2626;
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 5px;
            font-size: 14px;
        }

        .logout:hover {
            background: #b91c1c;
        }
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
	.user-area {
    	display: flex;
    	align-items: right;
    	gap: 15px;
	}
	.username {
    	font-size: 16px;
    	color: #e5e7eb;
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

include 'db_connect.php';
// include 'service/subroutines/fetch_last_day_diff.php';

$username = $_SESSION['username'];
$fullname = $_SESSION['fullname'];
$gainloss_total_value = 0;
$gainloss_percent_total_value = 0;
$purchase_total_value = $_SESSION['purchase_total_value'];
$current_total_value = $_SESSION['current_total_value'];
if ($purchase_total_value > 0) {
$gainloss_total_value = $current_total_value - $purchase_total_value;
$gainloss_percent_total_value = ($gainloss_total_value / $purchase_total_value) * 100; }
?>
<body>
    <div class="titlebar">
        <div class="title">My Dashboard</div>
         <div class="user-area">
          <span class="username">
                <?php echo htmlspecialchars($_SESSION['fullname']); ?>
          </span>
         </div>
       <a href="../logout.php" class="logout" onclick="return confirm('Are you sure you want to logout?');">Logout</a>
    </div>
   <br>

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
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Gain Loss Value: </p></th><p><?php $class = ($gainloss_total_value >= 0) ? "profit" : "loss"; echo "<td class='$class'>".number_format($gainloss_total_value, 2)."</td>"; ?></p></tr>
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Gain Loss Percent Value: </p></th><p><?php $class = ($gainloss_percent_total_value >= 0) ? "profit" : "loss"; echo "<td class='$class'>".number_format($gainloss_percent_total_value, 2)." %</td>"; ?></p></tr>
    </table>
    <h6> </h6>    
    <button type="button" onclick="window.location.reload();">Refresh Values</button>
    </div>
</div>

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
	<th>Diff</th>
	<th>Percentage</th>
        <th>Action</th>
    </tr>

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
        // parse NAV data for last day change
        $filePath = "cache/nav/{$isincode}.nav.txt";
        $currentnav1 = null; $currentnav2 = null; $diff = 0; $diffvalue = 0;
        if (file_exists($filePath)) {
                        $lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
                        if (!empty($lines)) {
                        $lastLine = end($lines);
                        $fields = str_getcsv($lastLine, ';');
                        $currentnav1 = $fields[count($fields) - 2];
                        if (count($lines) >= 2) {
                                $secondLastLine = $lines[count($lines) - 2];
                                $fields = str_getcsv($secondLastLine, ';');
                                $currentnav2 = $fields[count($fields) - 2];
                                if ($currentnav2) {
                                        $diff = $currentnav1 - $currentnav2;
					$diffvalue = $units_initial_value * $diff;
                                }
                        } else {
                                $currentnav2 = 0;
                                $diff = 0;
                        }
                        }
        }
        // parse NAV data for last day change end
    	echo "<tr>";
        echo "<td><strong>".$fundname."</strong></td>";
        echo "<td><b>".$isincode."</b></td>";
        echo "<td><b>".$currentDate."</b></td>";
        echo "<td><b>".number_format($currentnav_initial_value, 2)."</b></td>";
        echo "<td><b>".round($units_initial_value, 2)."</b></td>";
        echo "<td><b>".round($purchase_initial_value, 2)."</b></td>";
        echo "<td><b>".round($current_initial_value, 2)."</b></td>";
        $class = ($gainloss_initial_value >= 0) ? "profit" : "loss";
		echo "<td class='$class'>".$gainloss_initial_value."</td>";
        $class = ($diffvalue >= 0) ? "profit" : "loss";
		echo "<td class='$class'>".number_format($diffvalue, 2)."</td>";
        $class = ($percentage_value >= 0) ? "profit" : "loss";
        	echo "<td class='$class'>".number_format($percentage_value, 2)." %</td>";
		?>
    	<td>
        <button title="Purchase Details" style="background-color: #008000" class="btn btn-primary viewBtn1" data-toggle="modal" data-target="#Modal1"
                data-id="<?php echo $isincode; ?>">
            <i class="bi bi-eye"></i>
        </button>
        <button title="Add Units" style="background-color:orange" class="btn btn-primary viewBtn2" data-toggle="modal" data-target="#Modal2"
                data-id="<?php echo $isincode; ?>">
            <i class="bi bi-bag-plus-fill"></i>
        </button>
        <button title="Show NAV Graph" style="background-color: #ac7339" class="btn btn-primary viewBtn3" data-toggle="modal" data-target="#Modal3"
                data-id="<?php echo $isincode; ?>">
            <i class="bi bi-bar-chart-fill"></i>
        </button>
        <button title="Delete Fund" style="background-color: #cc0000" class="btn btn-primary viewBtn4" data-toggle="modal" data-target="#Modal4"
                data-id="<?php echo $isincode; ?>">
            <i class="bi bi-trash"></i>
        </button>
    	</td>
        <?php
        echo "<tr>";
}
echo "<hr>";    
$conn->close();
?>
    
</table>
</div>
    
<?php 
if ($purchase_total_value > 0) {
$gainloss_total_value = $current_total_value - $purchase_total_value; 
$gainloss_percent_total_value = ($gainloss_total_value / $purchase_total_value) * 100; }
$_SESSION['purchase_total_value'] = $purchase_total_value;
$_SESSION['current_total_value'] = $current_total_value;
echo "<hr>";
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
        <div class="modal-overlay modal-content">
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

<!-- Modal -->
<div class="modal fade" id="Modal3">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Fund Details</h5>
                <button class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body" id="modalBody3">
                Loading...
            </div>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="Modal4">
    <div class="modal-dialog modal-lg">
        <div class="modal-overlay modal-content">
            <div class="modal-box modal-close-btn modal-header">
                <button class="btn-close"
                        data-bs-dismiss="modal">
                </button>
            </div>
            <div class="modal-body" id="modalBody4">
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

<script>

$(document).on("click", ".viewBtn3", function () {
    var id = $(this).data("id");
    $("#Modal3").modal("show");
    $("#modalBody3").html("Loading...");
    $("#modalBody3").load(
        "charts/fund_chart5.php?id=" +
        encodeURIComponent(id),
        function (response, status, xhr) {
            if (status === "error") {
                console.error(
                    "Error loading chart:",
                    xhr.status,
                    xhr.statusText
                );
                $("#modalBody3").html(
                    "<p style='color:red;'>Unable to load chart.</p>"
                );
                return;
            }
            console.log(
                "fund_chart5.php loaded successfully"
            );
        }
    );
});

</script>

<script>

$(document).on("click",".viewBtn4",function(){

    var id=$(this).data("id");
    $("#modalBody4").html("Loading...");
    $("#modalBody4").load("data-service/data_delete_modal.php?id="+id);
    $("#Modal4").modal("show");

});

</script>

<?php include 'charts/fund_chart4.php'; ?>

</body>
</html>

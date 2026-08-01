<!DOCTYPE html>
<head>
    <link rel="stylesheet" href="../mftracker-v01/css/style3.css">
    <link rel="icon" type="image/x-icon" href="icons/golden-indian-rupee.ico">
</head>
    
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}

include 'db_connect.php';

$username = $_SESSION['username'];
$fullname = $_SESSION['fullname'];
// $fullname = $_SESSION['fullname'];
?>
<head>
<h2>Mutual Fund Details - <?php echo $fullname; ?></h2>
</head>
<div class="menu">
    <p>➜ Enter Recent Purchases
        <a href="data_entry.php">Click Here</a>
    </p>

    <p>➜ Add New Mutual Fund
        <a href="data_entry_new.php">Click Here</a>
    </p>

    <p>➜ Download Latest NAV from AMFI and update
        <a href="download_update_nav.php">Click Here</a>
    </p>
    
     <p>➜ Logout
        <a href="../logout.php">Click Here</a>
    </p>
</div>

<?php
// Get unique fund names
$sql = "SELECT DISTINCT Fund_Name FROM `" . $_SESSION['tablename'] . "`";
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
    <link rel="stylesheet" href="../mftracker-v01/css/style3.css">
    <style>
    body {
      background-color: #e6e6ff;
      color: darkblue;
    }
    </style>
</head>
<body>

<div class="container mt-4">

<table>
    <tr>
        <th class="fund-name">Fund_Name</th>
        <th>ISIN_Code</th>
        <th>Date</th>
        <th>Current_NAV</th>
        <th>Units</th>
        <th>Purchase Total Value</th>
        <th>Current Total Value</th>
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
    }
    $percentage_value = ($gainloss_initial_value / $purchase_initial_value) * 100;
    echo "<hr>";
    	echo "<tr>";
        echo "<td class='fund-name'>".$fundname."</td>";
        echo "<td>".$isincode."</td>";
        echo "<td>".$currentDate."</td>";
        echo "<td>".$currentnav_initial_value."</td>";
        echo "<td>".$units_initial_value."</td>";
        echo "<td>".$purchase_initial_value."</td>";
        echo "<td>".$current_initial_value."</td>";
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

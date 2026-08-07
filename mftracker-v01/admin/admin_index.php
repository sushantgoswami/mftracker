<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: ../login.php");
    exit;
}
if (isset($_SESSION['msg'])) { echo "<script> alert('" . addslashes($_SESSION['msg']) . "'); </script>"; unset($_SESSION['msg']); }

include '../db_connect.php';

$username = $_SESSION['username'];

?>

<!DOCTYPE html>
<head>
    <h2>Administrator mftracker</h2>
    <link rel="icon" type="image/x-icon" href="../icons/golden-indian-rupee.ico">
    <link href="../bootstrap/bootstrap.min.css" rel="stylesheet">
    <script src="../bootstrap/jquery-3.7.1.min.js"></script>
    <script src="../bootstrap/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="../css/style5.css">
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
	  width: 60px;
      height: 40px;
      padding: 10px;
      background-color: orange;
    }
    </style>
</head>
<div class="menu parent-container-div">
    <div class="child-box">
    <p>➜ Check Latest User Messages <a href="check_messages.php">Click Here</a></p>
    <p>➜ Add New Mutual Fund <a href="data-service/data_entry_new.php">Click Here</a></p>
    <p>➜ Update Latest NAV for ALL Users <a href="all_download_update_nav.php">Click Here</a></p>
    <p>➜ Reset Password for Admin User <a href="admin_reset_password.php">Click Here</a></p>
    <p>➜ Logout <a href="../../logout.php">Click Here</a></p>
    </div>
    <div class="child-box">
    <p>➜ Download All fund data in CSV <a href="all_csvdata_download.php" target="_blank">Click Here</a></p>
    <p>➜ Restore All fund data in CSV <a href="all_csvdata_upload.php" target="_blank">Click Here</a></p>   
    <p>➜ Download ALL fund data in SQL <a href="all_sqldata_download.php">Click Here</a></p>     
    <p>➜ Upload ALL fund data in SQL <a href="all_sqldata_upload.php">Click Here</a></p>
    </div>
    <div class="child-box">
    <table>
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Total User Created: </p></th><th><p>Click Here</p></th></tr>
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Total Tables Created: </p></th><th><p>Click Here</p></th></tr>
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Total Mutual Funds Used: </p></th><th><p>Click Here</p></th></tr>
    <tr><th style="background-color: #eeffcc; color:green;"><p>➜ Currently logged in Users: </p></th><th><p>Click Here</p></th></tr>
    </table>
    
    <button type="button" onclick="window.location.reload();">Refresh Values</button>
    </div>
</div>

<?php

// Get unique user names
include '../db_connect.php';
$sql = "SELECT * FROM users WHERE username != 'administrator' ORDER BY username";
$result = $conn->query($sql);

?>

<body>

<div class="table-container container">

<table>
    <tr>
        <th>User ID</th>
        <th>User Name</th>
        <th>Full Name</th>
        <th>Email ID</th>
        <th>Table Name</th>
        <th>Action</th>
    </tr>

<?php
    while ($row = $result->fetch_assoc()) {
    	echo "<tr>";
        echo "<td>".$row['id']."</td>";
        echo "<td>".$row['username']."</td>";
        $username_user =  $row['username'];
    	echo "<td>".$row['fullname']."</td>";
    	echo "<td>".$row['email']."</td>";
    	echo "<td>".$row['tablename']."</td>";
		?>
    	<td>
        <button class="btn btn-primary viewBtn1" data-toggle="modal" data-target="#Modal1"
                data-id="<?= $username_user; ?>">
            Reset Password
        </button>
        <button style="background-color:orange" class="btn btn-primary viewBtn2" data-toggle="modal" data-target="#Modal2"
                data-id="<?= $username_user; ?>">
            Delete
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
<div class="modal fade" id="Modal1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content modal-overlay">
            <div class="modal-box modal-close-btn modal-header">
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
                <h5 class="modal-title">Delete User</h5>
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
    $("#modalBody1").load("reset_user_password.php?id="+id);
    $("#Modal1").modal("show");

});

</script>
<script>

$(document).on("click",".viewBtn2",function(){

    var id=$(this).data("id");
    $("#modalBody2").html("Loading...");
    $("#modalBody2").load("delete_user.php?id="+id);
    $("#Modal2").modal("show");

});

</script>

</body>  
</html>
   
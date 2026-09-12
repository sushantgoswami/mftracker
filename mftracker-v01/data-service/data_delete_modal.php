<?php

session_start();

include '../db_connect.php';

if (!isset($_SESSION['username'])) { header("Location: ../../index.php"); exit; }

$username_user = $_SESSION['username'];
$tablename_user = $_SESSION['tablename'];
$isincode_user = (string) $_GET['id'];

$Captcha = random_int(10000, 99999);
$_SESSION["Captcha"] = $Captcha;

$stmt1 = $conn->prepare("SELECT Fund_Name FROM `" . $_SESSION['tablename'] . "` WHERE ISIN_Code = ? LIMIT 1");
$stmt1->bind_param("s", $isincode_user);   // i = integer, s = string
$stmt1->execute();
$result1 = $stmt1->get_result();
while ($row1 = $result1->fetch_assoc()) {
    $fundname_user = $row1['Fund_Name'];
}
$stmt1->close();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Delete Fund data</title>
    <link rel="icon" type="image/x-icon" href="../icons/golden-indian-rupee.ico">
    <link rel="stylesheet" href="css/data_delete_modal.css">
</head>

<body>
<div class="form-container">
<form class="fancy-form" name=inputform action="data-service/data_delete_modal_check.php" method="POST">
    <h2>Confirm Delete Fund</h2>

    <div class="input-group">
    <input type="text" id="userid" name="userid" value="<?php echo $username_user; ?>" readonly>
    <label for="userid">userid</label>
    </div>

    <div class="input-group">
    <input type="text" id="fundname_user" name="fundname_user" value="<?php echo $fundname_user; ?>" readonly>
    <label for="fundname_user">fundname</label>
    </div>

    <div class="input-group">
    <input type="text" id="tablename_user" name="tablename_user" value="<?php echo $tablename_user; ?>" readonly>
    <label for="tablename_user">tablename</label>
    </div>

    <!-- Native Accent Checkbox -->
    <div class="input-group">
    <input type="text" id="isincode_user" name="isincode_user" value="<?php echo $isincode_user; ?>" readonly>
    <label for="isincode_user">isincode</label>
    </div>

    <div class="checkbox-group">
    <input type="checkbox" id="funddelete" name="funddelete" value="yes">
    <label for="funddelete">Confirm delete fund</label>
    </div>

    <div class="form-row">
    <div class="input-group">
    <input type="text" id="Captcha" value="<?php echo $Captcha;?>" name="Captcha" disabled>
    <label for="Captcha">Captcha</label>
    </div>
    <div class="input-group">
    <input type="text" id="Verify" name="Verify">
    <label for="Verify">Verify</label>
    </div></div>

    <button type="submit" class="submit-btn" name="submit">Delete Fund</button>

</form>
</div>

</body>
</html>


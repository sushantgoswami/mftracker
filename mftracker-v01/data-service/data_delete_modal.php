<?php


include 'db_connect.php';

// if (!isset($_SESSION['username'])) { header("Location: ../../index.php"); exit; }

$username_user = $_SESSION['username'];
$isincode_user = (string) $_GET['id'];

$Captcha = random_int(10000, 99999);
$_SESSION["Captcha"] = $Captcha;

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Enter the Purchase data</title>
    <link rel="icon" type="image/x-icon" href="../icons/golden-indian-rupee.ico">
    <link rel="stylesheet" href="css/data_delete_modal.css">
    <!-- <h2>MF Information Form, Enter the Purchase data</h2> -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
<div class="form-container">
<form class="fancy-form" name=inputform action="delete_table_check.php" method="POST">
    <h2>Confirm Delete Fund</h2>

    <div class="input-group">
    <input type="text" id="userid" name="userid" value="<?php echo $username_user; ?>" readonly>
    <label for="userid">userid</label>
    </div>

    <div class="input-group">
    <input type="text" id="tablename_user" name="tablename_user" value="<?php echo $tablename_user; ?>" readonly>
    <label for="tablename_user">tablename</label>
    </div>

    <!-- Native Accent Checkbox -->
    <div class="checkbox-group">
    <input type="checkbox" id="tabledelete" name="tabledelete" value="yes">
    <label for="tabledelete">Confirm delete table</label>
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

    <button type="submit" class="submit-btn" name="submit">Delete Table</button>

</form>
</div>

</body>
</html>


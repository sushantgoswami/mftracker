<?php

include '../db_connect.php';
include '../config/encrypt_code.php';

$id = (string)$_GET['id'];

if ($id == $encryptedcode) {

$administrator = "administrator";

$stmt = $conn->prepare("SELECT * FROM users WHERE username != ?");
$stmt->bind_param("s", $administrator);
$stmt->execute();
$result = $stmt->get_result();
                
while ($row = $result->fetch_assoc()) {
  $tablename_user = $row['tablename'];
  $username_user = $row['username'];
  // echo $username_user;
  // echo $tablename_user;
  //
  $purchase_total_value = 0;
  $current_total_value = 0;

  $sql = "SELECT * FROM `" . $tablename_user . "`";
  $result1 = $conn->query($sql);

  $current_initial_value = 0;
  $purchase_initial_value = 0;
  $units_initial_value = 0;
  $gainloss_initial_value = 0;
  $currentnav_initial_value = 0;

  while ($row1 = $result1->fetch_assoc())
        {
    $current_initial_value = $current_initial_value + $row1['Current_Value'];
    $purchase_initial_value = $purchase_initial_value + $row1['Purchase_Value'];
  }
  $current_total_value = intval($current_initial_value);
  $purchase_total_value = intval($purchase_initial_value);
  $gainloss = intval($current_total_value - $purchase_total_value);
  if ($gainloss != 0) {
  $gainlosspercent = ($gainloss / $purchase_total_value) * 100;
  $gainlosspercent = round($gainlosspercent, 2); }
  // echo $gainlosspercent;
  // echo $current_initial_value;
  // echo $purchase_initial_value;
  $currentdate = date('d-m-y');
  $filename = "../cache/totalvalue/$username_user.csv";
  $today = date("Y-m-d");
  $fileDate = date("Y-m-d", filemtime($filename));
  if ($fileDate == $today) {
   echo "Not appending data";
  } else {
   $file = fopen($filename, 'a');
   $data = array($currentdate, $purchase_total_value, $current_total_value, $gainloss, $gainlosspercent);
   fputcsv($file, $data);
   fclose($file);
  }
}
$stmt->close();
}

?>

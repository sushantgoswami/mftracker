<!DOCTYPE html>
<html>
<body>
<?php
    
session_start();
include '../db_connect.php';
include '../config/encrypt_code.php';

$filename = '../cache/amfinav/NAVAll.txt';
$id = (string)$_GET['id'];
$context = stream_context_create([
    'ssl' => [
        'verify_peer'      => false,
        'verify_peer_name' => false,
    ]
]);
if ($id == $encryptedcode) {
// main code start
// Check if file exists to prevent errors
if (file_exists($filename)) {
    // Get file modification time and current time
    $fileTime = filemtime($filename);
    $currentTime = time();

    // Check if the difference is less than 3600 seconds (1 hour)
    if (($currentTime - $fileTime) <= 3600) {
        echo "The file was modified within the last hour. Not downloading data from AMFI. <br>";
        echo "Proceeding with local NAV file.<br>";
    } else {
        echo "The file is older than 1 hour. Proceeding to download.. <br>";
		$url = "https://portal.amfiindia.com/spages/NAVAll.txt";
		$savePath = "../cache/amfinav/NAVAll.txt";
		$remoteFile = fopen($url, 'r', false, $context);
		if ($remoteFile) {
    		$result = file_put_contents($savePath, $remoteFile);    
    	if ($result !== false) {
        echo "File downloaded successfully!<br>";
    	} else {
        echo "Failed to save the file.<br>";
    }
} else {
    echo "Could not open the remote URL.";
} 
    }
} else {
    echo "File does not exist.";
    $url = "https://portal.amfiindia.com/spages/NAVAll.txt";
    $savePath = "../cache/amfinav/NAVAll.txt";
    $remoteFile = fopen($url, 'r', false, $context);
    if ($remoteFile) {
    $result = file_put_contents($savePath, $remoteFile);
    if ($result !== false) {
        echo "File downloaded successfully!<br>";
    } else {
        echo "Failed to save the file.<br>";
    }
    }
}

// Get unique fund names
   
// SQL query to exclude the 'admin' table
$tablequery = "SHOW TABLES WHERE `Tables_in_$dbname` != 'users' AND `Tables_in_$dbname` != 'administrator26131'";
$stmt1 = $conn->query($tablequery);

while ($rowtable = $stmt1->fetch_array()) {
        $tablenamex = $rowtable[0];
        // main code
        // Get unique fund names
		$sql = "SELECT DISTINCT ISIN_Code FROM $tablenamex";
		$result = $conn->query($sql);
		while ($row = $result->fetch_assoc()) 
		{
	
    	$isincode = $row['ISIN_Code'];
	
    	// Query for each unique fund
    	$sql2 = "SELECT * FROM $tablenamex WHERE ISIN_Code='$isincode'";
    	$result2 = $conn->query($sql2);
    	while ($row2 = $result2->fetch_assoc()) 
			{
    	    // echo "<td>".$row2['ISIN_Code']."</td>";
    	    // bigin loop
    	 
    		$isin = $row2['ISIN_Code'];    // Replace with your ISIN
			$lines = file("../cache/amfinav/NAVAll.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
			foreach ($lines as $line) 
				{
	
				$fields = explode(";", $line);
	
				// Skip invalid lines
				if (count($fields) < 6) {
				continue;
				}
	
				// Compare both ISIN columns
				if (strcasecmp(trim($fields[1]), $isin) == 0 ||
				strcasecmp(trim($fields[2]), $isin) == 0) {
	
				$schemeCode = $fields[0];
				$schemeName = $fields[3];
				$nav        = $fields[count($fields) - 2];
				$date       = $fields[count($fields) - 1];
    	    
				$stmt = $conn->prepare("UPDATE $tablenamex SET Current_NAV = ? WHERE ISIN_Code = ?");
				$stmt->bind_param("ss", $nav, $isin);
				$stmt->execute();
				}
				}   
			}
		}
        // main code end
}
    

$stmt->close();

// graph data update
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
   $data = array($currentdate, $purchase_total_value, $current_total_value, $gainloss);
   fputcsv($file, $data);
   fclose($file);
  }
}
$stmt->close();
// end graph data update

$conn->close();

// Clear and readable log message format
$logFolder = "../log/";
$logFile = "update_nav.log";
$logMessage = "[" . date("d-m-Y H:i:s") . "] " . basename($_SERVER['PHP_SELF']) . " (MSG:0001): file executed successfully." . PHP_EOL;
$_SESSION['logMessage'] = $logMessage;
$_SESSION['logFile'] = "$logFolder$logFile";
include '../log/logger.php';
unset($_SESSION['logMessage']);
unset($_SESSION['logFile']);
// Clear and readable log message format
    
sleep(1);
} 
// main code end
?>
</body>
</html>

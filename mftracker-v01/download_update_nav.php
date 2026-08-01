<!DOCTYPE html>
<html>
<body>    
<p style="padding-left:20%;"><font color=Red><font size="3">Return to Main Page <a href="index.php">Click Here</a></font></font></p>
</body>
</html>
 
<?php
    
session_start();
include 'db_connect.php';

$filename = 'NAVAll.txt';

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
		$savePath = "NAVAll.txt";
		$remoteFile = fopen($url, 'r');
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
}

// Get unique fund names
$sql = "SELECT DISTINCT ISIN_Code FROM sushantgoswami01";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {

    $isincode = $row['ISIN_Code'];

    // Query for each unique fund
    $sql2 = "SELECT * FROM sushantgoswami01 WHERE ISIN_Code='$isincode'";
    $result2 = $conn->query($sql2);

    // echo "<h3>$isincode</h3>";

    while ($row2 = $result2->fetch_assoc()) 
	{
        // echo "<td>".$row2['ISIN_Code']."</td>";
        // bigin loop
     
    	$isin = $row2['ISIN_Code'];    // Replace with your ISIN
		$lines = file("NAVAll.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		foreach ($lines as $line) {

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
        $nav        = $fields[4];
        $date       = $fields[5];

        // echo "Scheme Code : $schemeCode<br>";
        // echo "Scheme Name : $schemeName<br>";
        // echo "ISIN        : $isin<br>";
        // echo "NAV         : $nav<br>";
        // echo "Date        : $date<br>";
        
        $stmt = $conn->prepare("UPDATE sushantgoswami01 SET Current_NAV = ? WHERE ISIN_Code = ?");
        $stmt->bind_param("ss", $nav, $isin);

        if ($stmt->execute()) {
        echo "Data saved successfully. - $schemeCode $isin $nav<br>";
        } else {
        echo "Error: ";
        }
    	}
		}
    // end of loop    
    }
}
$sql->close();
$sql2->close();
$stmt->close();
$conn->close();
?>

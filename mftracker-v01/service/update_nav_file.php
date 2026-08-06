<?php
    
session_start();
include 'db_connect.php';

// Get unique fund names
$sql = "SELECT DISTINCT ISIN_Code FROM `" . $_SESSION['tablename'] . "`";
$result = $conn->query($sql);

while ($row = $result->fetch_assoc()) {

    $isincode = $row['ISIN_Code'];

    // Query for each unique fund
    $sql2 = "SELECT * FROM `" . $_SESSION['tablename'] . "` WHERE ISIN_Code='$isincode'";
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
        
        $stmt = $conn->prepare("UPDATE `" . $_SESSION['tablename'] . "` SET Current_NAV = ? WHERE ISIN_Code = ?");
        $stmt->bind_param("ss", $nav, $isin);
		$stmt->execute();
    	}
		}
    // end of loop    
    }
}
$stmt->close();
$conn->close();
// header("Location: ../index.php");
// exit();
?>
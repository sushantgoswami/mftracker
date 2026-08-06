<?php
    
session_start();

include '../db_connect.php';

if (!isset($_SESSION['username'])) { header("Location: ../noindex.php"); exit; }
if (isset($_SESSION['msg'])) { echo "<script> alert('" . addslashes($_SESSION['msg']) . "'); </script>"; unset($_SESSION['msg']); }

$userInput = $_POST['csvdata'];
$dateformat = $_POST['date'];
$outputlog = "End";        
// $safeInput = htmlspecialchars($userInput);
        
$lines = explode("\n", $userInput);

foreach ($lines as $line) {
    // Skip empty lines to prevent errors
    if (empty(trim($line))) continue;

    // 3. Separate the line data by commas into an array
    $rowFields = str_getcsv($line); 
    $isincode = $rowFields[0];
    $fundname = $rowFields[1];
    $date = $rowFields[2];
    $purchasenav = $rowFields[3];
    $units = $rowFields[4];
    $currentnav = 0;
    // Output the resulting array for each line
    // print_r($rowFields);

    if ($dateformat == 'date0') {
    $dateformatted = $date; }
    // date formatting
    if ($dateformat == 'date1' || $dateformat == 'date2' || $dateformat == 'date3' || $dateformat == 'date4') {
	$parts = explode("/", $date);
	$date01 = $parts[0]; $date02 = $parts[1]; $date03 = $parts[2];
    if ($dateformat == 'date1') {
	$dateformatted = "{$date03}-{$date02}-{$date01}"; }
    if ($dateformat == 'date2') {
	$dateformatted = "{$date03}-{$date01}-{$date02}"; }
    if ($dateformat == 'date3') {
	$dateformatted = "20{$date03}-{$date02}-{$date01}"; }        
    if ($dateformat == 'date4') {
	$dateformatted = "20{$date03}-{$date01}-{$date02}"; }        
    }
    // data entry
    if (isset($isincode) || isset($fundname) || isset($dateformatted) || isset($purchasenav) || isset($units)) {
    $stmt = $conn->prepare("INSERT INTO `" . $_SESSION['tablename'] . "` (ISIN_Code, Fund_Name, Date, Current_NAV, Purchase_NAV, Units) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $isincode, $fundname, $dateformatted, $currentnav, $purchasenav, $units);

    if ($stmt->execute()) {
        $singlelog = "Data saved successfully for {$dateformatted}";
        $outputlog = "{$singlelog}-{$outputlog}";
    } else {
        $singlelog = "Error: saving data for {$dateformatted}";
        $outputlog = "{$singlelog}-{$outputlog}";
    }
    }
}
$stmt->close();
$conn->close();
$_SESSION['msg'] = $outputlog;
header("Location: csvdata_upload.php");
?>

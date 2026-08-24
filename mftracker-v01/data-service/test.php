<?php
$isincode = 'INF200K01UG1';    
// $filePath = "../cache/nav/{$isincode}.nav.txt";
$filePath = "../NAVAlla.txt";
echo $filePath;
//		function getFileStatus($filePath) {
    	if (file_exists($filePath)) {
//			$lines = file($filePath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
//			foreach ($lines as $line) {
//    		if (strpos($line, $isincode) !== false) {
//        		$fields = str_getcsv($line, ';');
//        		$currentnav = $fields[4] ?? ''; // 6th field
//        		break;
            echo "file exists";
//    		}
//			}
   			} else {
//			$lines = file("../NAVAll.txt", FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
//			foreach ($lines as $line) {
//    		if (strpos($line, $isincode) !== false) {
//        		$fields = str_getcsv($line, ';');
//        		$currentnav = $fields[4] ?? ''; // 6th field
//        		break;
			echo "file do not exists";
    		}
//			}
//		} 

?>
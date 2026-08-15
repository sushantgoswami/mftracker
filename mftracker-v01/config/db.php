<?php
    
include 'db_config.php';

// Create connection
try {
 $conn = new mysqli($servername, $username, $password, $dbname);
} catch (mysqli_sql_exception $e) {
 die("Database_connection_failed."); 
}

// Check connection
// if ($conn->connect_error) {
//    die("Connection failed: " . $conn->connect_error);
// }
?>
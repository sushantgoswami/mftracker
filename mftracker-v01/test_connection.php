<?php
include "db_connect.php";

if ($conn) {
    echo "<h2 style='color:green;'>Database Connection Successful!</h2>";
    echo "<p>Connected to database: <strong>" . $dbname . "</strong></p>";
} else {
    echo "<h2 style='color:red;'>Database Connection Failed!</h2>";
}
?>
<?php
echo password_hash("Tsmlucknow@2025", PASSWORD_DEFAULT);
?>


<?php
session_start();
include "../db_connect.php";

$username = $_POST['username'];
$fullname = $_POST['fullname'];
$email = $_POST['email'];
$question1 = $_POST['question1'];
$answer1 = $_POST['answer1'];
$captcha_verify = $_POST['Verify'];
$captcha = $_SESSION['Captcha'];
$secureNumber = random_int(10000, 99999);
$tablename = "$username$secureNumber";
// $password = $_POST['password'];
$password = password_hash($_POST['password'], PASSWORD_DEFAULT);

if ($captcha_verify != $captcha) {
 $_SESSION['msg'] = "Captcha code mismatch";
 header("Location: register.php"); 
 exit;
}

    $stmt = $conn->prepare("INSERT INTO users(username,password,email,fullname,tablename,question1,answer1) VALUES(?,?,?,?,?,?,?)");
    $stmt->bind_param("sssssss", $username, $password, $email, $fullname, $tablename, $question1, $answer1);

    if ($stmt->execute()) {
        $_SESSION['msg'] = "Registration Successful";        
    } else {
        $_SESSION['msg'] = "Registration Unsuccessful, try with other username.";
        header("Location: register.php");
        exit;
    }

// header("Location: login.php");
// exit;
$stmt->close();
$conn->close();
?>
<?php
session_start();  
include '../db_connect.php';

// 5. Construct the SQL query with backticks around the variable
$sql = "CREATE TABLE `{$tablename}` (
  `id` int(10) AUTO_INCREMENT PRIMARY KEY,
  `Masterdata` int(2) NOT NULL DEFAULT 0,
  `Fund_Name` varchar(50) NOT NULL,
  `ISIN_Code` varchar(15) NOT NULL,
  `Date` date NOT NULL,
  `Current_NAV` float NOT NULL,
  `Purchase_NAV` float NOT NULL,
  `Units` float NOT NULL,
  `Current_Value` float(8,2) GENERATED ALWAYS AS (`Current_NAV` * `Units`) VIRTUAL,
  `Purchase_Value` float(8,2) GENERATED ALWAYS AS (`Purchase_NAV` * `Units`) VIRTUAL,
  `Gain_Loss` float(8,2) GENERATED ALWAYS AS (`Current_Value` - `Purchase_Value`) VIRTUAL,
  `Percentage` float(8,2) GENERATED ALWAYS AS (`Gain_Loss` / `Purchase_Value` * 100) VIRTUAL
)";

// 6. Prepare and execute the query using the statement object
$stmt1 = $conn->prepare($sql);

if ($stmt1->execute()) {
    $_SESSION['msg'] = "Registration Successful, Data Table Creation Successful";
} else {
    $_SESSION['msg'] = "Registration Successful, Data Table Creation is not Successful";
}

// 7. Close connections
$conn->close();
$stmt1->close();
header("Location: ../../index.php");
exit;
?>
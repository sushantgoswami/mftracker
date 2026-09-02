<?php

session_start();
if (!isset($_SESSION['username'])) {header("Location: ../../login.php"); exit;}

$data = [];
$username_user = $_SESSION['username'];
$filename = "../cache/totalvalue/$username_user.csv";
// echo $username_user;
// echo $filename;

if (($handle = fopen($filename, "r")) !== false) {

    while (($row = fgetcsv($handle)) !== false) {
            $date = $row[0];
            $totalPurchase = (float)$row[1];
            $currentValue = (float)$row[2];

            $data[] = [
                "Date" => $date,
                "Total_Purchase" => $totalPurchase,
                "Current_Value" => $currentValue
            ];
    }

    fclose($handle);
}
// print_r($data);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Daywise Chart</title>

    <script src="js/chart.js"></script>
</head>

<body>

<h2>Value Data</h2>

<canvas id="myChart"></canvas>

<script>

const csvData = <?php= json_encode($data) ?>;

const dates = csvData.map(row => row.Date);
const purchases = csvData.map(row => row.Total_Purchase);
const values = csvData.map(row => row.Current_Value);

const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'line',

    data: {
        labels: dates,

        datasets: [
            {
                label: 'Total_Purchase',
                data: purchases,
                backgroundColor: 'blue'
            },
            {
                label: 'Current_Value',
                data: values,
                backgroundColor: 'red'
            }
        ]
    },

    options: {
        responsive: true,

        scales: {
            y: {
                beginAtZero: true
            }
        }
    }
});

</script>

</body>
</html>


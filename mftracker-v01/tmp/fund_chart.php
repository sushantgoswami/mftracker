<?php

session_start();
if (!isset($_SESSION['username'])) {header("Location: ../../login.php"); exit;}

$data = [];
$username_user = $_SESSION['username'];
$filename = "cache/totalvalue/$username_user.csv";

$data = [];

if (($handle = fopen($filename, "r")) !== false) {

    while (($row = fgetcsv($handle)) !== false) {

        $data[] = [
            "date" => $row[0],
            "purchase" => (int)$row[1],
            "current" => (int)$row[2],
            "gainloss" => (int)$row[3]
        ];
    }

    fclose($handle);
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>CSV Chart</title>
    <script src="charts/js/chart.js"></script>
    <link rel="stylesheet" href="css/style5.css">
</head>

<body>

<h2>Gain Loss Chart</h2>

<canvas id="myChart" style="background-color: #fff3cd"></canvas>
<canvas id="myChart1" style="background-color: #b6e1ea"></canvas>

<script>
const data = <?= json_encode($data) ?>;
const labels = data.map(row => row.date);
const purchase = data.map(row => row.purchase);
const current = data.map(row => row.current);
const gainloss = data.map(row => row.gainloss);
const ctx = document.getElementById('myChart');
const ctx1 = document.getElementById('myChart1');

new Chart(ctx, {
    type: 'line',

    data: {
        labels: labels,

        datasets: [
            {
                label: 'Purchase Value',
                data: purchase,
                backgroundColor: 'green'
            },
            {
                label: 'Current Value',
                data: current,
                backgroundColor: 'red'
            }
        ]
    },

    options: {
        responsive: true,

        scales: {
            y: {
                beginAtZero: false
            }
        }
    }
});
new Chart(ctx1, {
    type: 'line',

    data: {
        labels: labels,

        datasets: [
            {
                label: 'Gainloss Value',
                data: gainloss,
                backgroundColor: 'blue'
            }
        ]
    },

    options: {
        responsive: true,

        scales: {
            y: {
                beginAtZero: false
            }
        }
    }
});
</script>

</body>
</html>


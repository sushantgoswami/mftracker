<?php

session_start();
if (!isset($_SESSION['username'])) {header("Location: ../../login.php"); exit;}

$data = [];
$username_user = $_SESSION['username'];
$filename = "cache/totalvalue/$username_user.csv";

$data = [];

if (($handle = fopen($filename, "r")) !== false) {

    // Skip header row
    // fgetcsv($handle);

    while (($row = fgetcsv($handle)) !== false) {

        $data[] = [
            "date" => $row[0],
            "purchase" => (float)$row[1],
            "current" => (float)$row[2]
        ];
    }

    fclose($handle);
}
print_r($data);
?>

<!DOCTYPE html>
<html>
<head>
    <title>CSV Chart</title>

    <script src="charts/js/chart.js"></script>
</head>

<body>

<h2>Gain Loss Chart</h2>

<div style="width: 400px; height: 250px;">
<canvas id="myChart"></canvas>
</div>

<script>

const data = <?= json_encode($data) ?>;

const labels = data.map(row => row.date);
const purchase = data.map(row => row.purchase);
const current = data.map(row => row.current);

const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'bar',

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

</script>

</body>
</html>


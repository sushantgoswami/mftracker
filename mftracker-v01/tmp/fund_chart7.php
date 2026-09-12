<?php

session_start();
if (!isset($_SESSION['username'])) {header("Location: ../../login.php"); exit;}

$username_user = $_SESSION['username'];
$fundisincode = $id;
$filename = "cache/nav/$fundisincode.nav.txt";
$data = [];
if (($handle = fopen($filename, "r")) !== false) {
     while (($row = fgetcsv($handle, 0, ';')) !== false) {
        $data[] = [
            "date" => $row[count($row) - 1],
            "navdata" => $row[count($row) - 2]
        ];
    }
    fclose($handle);
}


if (count($data) > 30) {
    $data = array_slice($data, -30);
}

print_r($data);

?>
<!DOCTYPE html>
<html>
<head>
    <title>CSV Chart</title>

    <script src="chart/js/chart.js"></script>
</head>

<body>

<h2>Gain Loss Chart</h2>

<div style="width: 400px; height: 250px;">
<canvas id="myChart"></canvas>
</div>

<script>

const data = <?= json_encode($data) ?>;

const labels = data.map(row => row.date);
const purchase = data.map(row => row.navdata);

const ctx = document.getElementById('myChart');

new Chart(ctx, {
    type: 'bar',

    data: {
        labels: labels,

        datasets: [
            {
                label: 'NAV Value',
                data: navdata,
                backgroundColor: 'green'
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


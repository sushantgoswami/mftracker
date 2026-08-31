<?php
// Initialize empty arrays to store chart labels and numerical data
$labels = [];
$data = [];

// Open the CSV file in read-only mode
if (($handle = fopen("data.csv", "r")) !== FALSE) {
    
    // Skip the first row if it contains headers (e.g., "Month, Sales")
    fgetcsv($handle); 
    
    // Loop through each remaining row of the CSV file
    while (($row = fgetcsv($handle, 1000, ",")) !== FALSE) {
        $labels[] = $row[0]; // First column (e.g., Month)
        $data[] = (float)$row[1];  // Second column (e.g., Sales numbers)
    }
    
    // Close the open file pointer
    fclose($handle);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>PHP Chart from CSV Data</title>
    <!-- Include Chart.js from CDN -->
    <script src="https://jsdelivr.net"></script>
    <style>
        .chart-container {
            width: 80%;
            margin: auto;
        }
    </style>
</head>
<body>

<div class="chart-container">
    <h2>Monthly Sales Data</h2>
    <!-- The canvas element where the chart will be drawn -->
    <canvas id="myChart"></canvas>
</div>

<script>
// Safely inject PHP arrays into JavaScript JSON arrays
const chartLabels = <?php echo json_encode($labels); ?>;
const chartData = <?php echo json_encode($data); ?>;

// Initialize the Chart.js configuration
const ctx = document.getElementById('myChart').getContext('2d');
const myChart = new Chart(ctx, {
    type: 'bar', // Type of chart: bar, line, pie, doughnut, etc.
    data: {
        labels: chartLabels,
        datasets: [{
            label: 'Total Sales ($)',
            data: chartData,
            backgroundColor: 'rgba(54, 162, 235, 0.5)',
            borderColor: 'rgba(54, 162, 235, 1)',
            borderWidth: 1
        }]
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

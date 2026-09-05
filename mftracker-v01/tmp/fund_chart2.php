<?php

session_start();

$username = $_SESSION['username'];
// $filename = "cache/totalvalue/" . $username . ".csv";
$filename = "cache/totalvalue/sushantgoswami.csv.back";

$data = [];

if (($handle = fopen($filename, "r")) !== false) {

    while (($row = fgetcsv($handle)) !== false) {

        // if (count($row) < 3) {
        //     continue;
        // }

        $data[] = [
            "date"      => $row[0],
            "purchase"  => (float)$row[1],
            "current"   => (float)$row[2]
        ];
    }

    fclose($handle);
}

print_r($data);

/*
 * Example:
 * Read alternate records from bottom.
 */
// $data = array_reverse($data);
// $alternate = [];
// foreach ($data as $index => $row) {
//    if ($index % 2 === 0) {
//        $alternate[] = $row;
//     }
// }

/*
 * Put them back in chronological order.
 */
// $data = array_reverse($alternate);

?>

<div class="chart-wrapper">

    <div class="chart-header">
        <div>
            <h4>Portfolio Value</h4>
            <span>Purchase vs Current Value</span>
        </div>

        <div class="chart-badge">
            Investment
        </div>
    </div>

    <div class="chart-container">
        <canvas id="valueChart"></canvas>
    </div>

</div>

<style>

.chart-wrapper {
    background: linear-gradient(145deg, #ffffff, #f4f7fb);
    border-radius: 18px;
    padding: 22px;
    box-shadow:
        0 10px 30px rgba(0,0,0,0.08),
        0 2px 8px rgba(0,0,0,0.04);
}

.chart-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
}

.chart-header h4 {
    margin: 0;
    font-weight: 700;
    color: #172033;
}

.chart-header span {
    color: #7b8494;
    font-size: 13px;
}

.chart-badge {
    background: #e9f7ef;
    color: #198754;
    padding: 7px 12px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
}

.chart-container {
    position: relative;
    height: 380px;
}

</style>

<script>

const chartData = <?= json_encode($data) ?>;

const labels = chartData.map(row => row.date);
const purchase = chartData.map(row => Number(row.purchase));
const current = chartData.map(row => Number(row.current));

const ctx = document.getElementById('valueChart');

const gradientPurchase = ctx
    .getContext('2d')
    .createLinearGradient(0, 0, 0, 380);

gradientPurchase.addColorStop(0, 'rgba(33, 150, 243, 0.25)');
gradientPurchase.addColorStop(1, 'rgba(33, 150, 243, 0.00)');


const gradientCurrent = ctx
    .getContext('2d')
    .createLinearGradient(0, 0, 0, 380);

gradientCurrent.addColorStop(0, 'rgba(40, 167, 69, 0.25)');
gradientCurrent.addColorStop(1, 'rgba(40, 167, 69, 0.00)');


new Chart(ctx, {

    type: 'line',

    data: {

        labels: labels,

        datasets: [

            {
                label: 'Purchase Value',

                data: purchase,

                borderColor: '#2196f3',

                backgroundColor: gradientPurchase,

                borderWidth: 3,

                fill: true,

                tension: 0.4,

                pointRadius: 3,

                pointHoverRadius: 7,

                pointBackgroundColor: '#ffffff',

                pointBorderColor: '#2196f3',

                pointBorderWidth: 2
            },

            {
                label: 'Current Value',

                data: current,

                borderColor: '#28a745',

                backgroundColor: gradientCurrent,

                borderWidth: 3,

                fill: true,

                tension: 0.4,

                pointRadius: 3,

                pointHoverRadius: 7,

                pointBackgroundColor: '#ffffff',

                pointBorderColor: '#28a745',

                pointBorderWidth: 2
            }

        ]
    },

    options: {

        responsive: true,

        maintainAspectRatio: false,

        interaction: {
            mode: 'index',
            intersect: false
        },

        plugins: {

            legend: {
                position: 'top',

                labels: {
                    usePointStyle: true,
                    padding: 20
                }
            },

            tooltip: {

                backgroundColor: '#172033',

                titleColor: '#ffffff',

                bodyColor: '#ffffff',

                padding: 12,

                cornerRadius: 10,

                displayColors: true,

                callbacks: {

                    label: function(context) {

                        return context.dataset.label +
                            ': Rs' +
                            Number(context.raw).toLocaleString('en-IN');

                    }

                }

            }

        },

        scales: {

            x: {

                grid: {
                    display: false
                },

                ticks: {
                    color: '#7b8494'
                }

            },

            y: {

                grid: {
                    color: 'rgba(0,0,0,0.06)'
                },

                ticks: {

                    color: '#7b8494',

                    callback: function(value) {

                        return 'Rs' +
                            Number(value).toLocaleString('en-IN');

                    }

                }

            }

        }

    }

});

</script>


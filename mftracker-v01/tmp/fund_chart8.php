<?php

session_start();

// Optional access protection
// if (!isset($_SESSION['username'])) {
//     http_response_code(403);
//     exit('Access denied');
// }

$username_user = $_SESSION['username'] ?? '';

$fundisincode = $id;
$filename = "cache/nav/$fundisincode.nav.txt";

$data1 = [];

if (($handle = fopen($filename, "r")) !== false) {

    while (($row = fgetcsv($handle, 0, ';')) !== false) {

        if (count($row) < 2) {
            continue;
        }

        $date = trim($row[count($row) - 1]);
        $nav  = trim($row[count($row) - 2]);

        if ($date === '' || $nav === '' || !is_numeric($nav)) {
            continue;
        }

        $data1[] = [
            "date"   => $date,
            "navdata" => (float) $nav
        ];
    }

    fclose($handle);
}

// Keep data in chronological order.
// Remove this if your file is already guaranteed to be oldest -> newest.
usort($data1, function ($a, $b) {
    return strtotime($a['date']) <=> strtotime($b['date']);
});

?>

<html>

<head>
    <script src="js/chart.js"></script>
</head>

<body>

<div class="investment-dashboard">

    <div class="chart-panel">

        <div class="panel-header">

            <div>
                <div class="panel-title">
                    <?php echo htmlspecialchars($id); ?>
                </div>

                <div class="panel-subtitle">
                    NAV Data Graph
                </div>
            </div>

            <div class="period-buttons">

                <button
                    type="button"
                    class="period-btn active"
                    data-period="1M">
                    1M
                </button>

                <button
                    type="button"
                    class="period-btn"
                    data-period="3M">
                    3M
                </button>

                <button
                    type="button"
                    class="period-btn"
                    data-period="6M">
                    6M
                </button>

                <button
                    type="button"
                    class="period-btn"
                    data-period="1Y">
                    1Y
                </button>

            </div>

        </div>

        <div class="chart-container">
            <canvas id="myChart1"></canvas>
        </div>

    </div>

</div>


<style>

/* =========================================
   GRAPH BUTTONS
   ========================================= */

.period-buttons {
    display: flex;
    gap: 6px;
    align-items: center;
}

.period-btn {
    border: 1px solid #d1d5db;
    background: #fff;
    color: #374151;
    padding: 6px 12px;
    border-radius: 6px;
    font-size: 12px;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.2s ease;
}

.period-btn:hover {
    background: #f3f4f6;
}

.period-btn.active {
    background: #2563eb;
    color: #fff;
    border-color: #2563eb;
}


/* =========================================
   MAIN DASHBOARD
   ========================================= */

.investment-dashboard {
    font-family:
        -apple-system,
        BlinkMacSystemFont,
        "Segoe UI",
        Roboto,
        Arial,
        sans-serif;

    background:
        linear-gradient(145deg,#f7f9fc,#eef2f7);

    padding: 22px;
    border-radius: 20px;
    color: #1c2434;
}


/* =========================================
   CHART PANEL
   ========================================= */

.chart-panel {
    background: #ffffff;
    border-radius: 17px;
    padding: 20px;
    margin-bottom: 18px;

    border: 1px solid rgba(0,0,0,.04);

    box-shadow:
        0 8px 30px rgba(31,45,61,.07);
}


/* =========================================
   HEADER
   ========================================= */

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}

.panel-title {
    font-weight: 750;
    font-size: 16px;
}

.panel-subtitle {
    color: #98a2b3;
    font-size: 12px;
    margin-top: 3px;
}


/* =========================================
   CHART
   ========================================= */

.chart-container {
    position: relative;
    width: 100%;
    height: 310px;
}


/* =========================================
   MOBILE
   ========================================= */

@media(max-width:700px) {

    .investment-dashboard {
        padding: 14px;
    }

    .panel-header {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }

    .period-buttons {
        width: 100%;
    }

    .period-btn {
        flex: 1;
    }

    .chart-container {
        height: 270px;
    }
}

</style>


<script>

/* =========================================
   PHP DATA -> JAVASCRIPT
   ========================================= */

const chartData1 = <?php
    echo json_encode(
        $data1,
        JSON_UNESCAPED_UNICODE |
        JSON_UNESCAPED_SLASHES |
        JSON_NUMERIC_CHECK
    );
?>;


/* =========================================
   CANVAS
   ========================================= */

const canvas1 = document.getElementById("myChart1");
const ctx1 = canvas1.getContext("2d");


/* =========================================
   GRADIENT
   ========================================= */

const purchaseGradient =
    ctx1.createLinearGradient(0, 0, 0, 310);

purchaseGradient.addColorStop(
    0,
    "rgba(33,150,243,.30)"
);

purchaseGradient.addColorStop(
    1,
    "rgba(33,150,243,.01)"
);


/* =========================================
   PERIOD FILTER
   ========================================= */

function getPeriodStart(period) {

    const today = new Date();

    const start = new Date(today);

    switch (period) {

        case "1M":
            start.setMonth(start.getMonth() - 1);
            break;

        case "3M":
            start.setMonth(start.getMonth() - 3);
            break;

        case "6M":
            start.setMonth(start.getMonth() - 6);
            break;

        case "1Y":
            start.setFullYear(start.getFullYear() - 1);
            break;

        default:
            return null;
    }

    return start;
}


/* =========================================
   FILTER DATA
   ========================================= */

function getFilteredData(period) {

    const startDate = getPeriodStart(period);

    if (!startDate) {
        return chartData1;
    }

    return chartData1.filter(row => {

        const rowDate = new Date(row.date);

        return !isNaN(rowDate) && rowDate >= startDate;

    });
}


/* =========================================
   INITIAL DATA
   ========================================= */

const initialData =
    getFilteredData("1M");

const labels =
    initialData.map(row => row.date);

const navdata =
    initialData.map(row => Number(row.navdata));


/* =========================================
   CREATE CHART
   ========================================= */

const navChart = new Chart(ctx1, {

    type: "line",

    data: {

        labels: labels,

        datasets: [
            {
                label: "NAV Value",

                data: navdata,

                borderColor: "#2196f3",

                backgroundColor:
                    purchaseGradient,

                borderWidth: 3,

                fill: true,

                tension: 0.42,

                pointRadius: 2,

                pointHoverRadius: 7,

                pointBackgroundColor:
                    "#ffffff",

                pointBorderColor:
                    "#2196f3",

                pointBorderWidth: 2
            }
        ]
    },


    options: {

        responsive: true,

        maintainAspectRatio: false,

        interaction: {
            mode: "index",
            intersect: false
        },


        plugins: {

            legend: {

                position: "top",

                labels: {

                    usePointStyle: true,

                    padding: 18,

                    font: {
                        size: 12
                    }
                }
            },


            tooltip: {

                backgroundColor:
                    "rgba(17,24,39,.94)",

                titleColor: "#ffffff",

                bodyColor: "#e5e7eb",

                padding: 13,

                cornerRadius: 10,

                displayColors: true,


                callbacks: {

                    label: function(context) {

                        return (
                            context.dataset.label +
                            ": ₹" +
                            Number(
                                context.raw
                            ).toLocaleString(
                                "en-IN",
                                {
                                    minimumFractionDigits: 2,
                                    maximumFractionDigits: 2
                                }
                            )
                        );
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

                    color: "#8994a4",

                    maxRotation: 0,

                    autoSkip: true,

                    maxTicksLimit: 8
                }
            },


            y: {

                beginAtZero: false,

                grid: {

                    color:
                        "rgba(148,163,184,.12)"
                },

                ticks: {

                    color: "#8994a4",

                    callback: function(value) {

                        return "₹" +
                            Number(value)
                            .toLocaleString(
                                "en-IN"
                            );
                    }
                }
            }
        }
    }
});


/* =========================================
   UPDATE CHART
   ========================================= */

function updateChart(period) {

    const filteredData =
        getFilteredData(period);

    navChart.data.labels =
        filteredData.map(row => row.date);

    navChart.data.datasets[0].data =
        filteredData.map(row =>
            Number(row.navdata)
        );

    navChart.update();
}


/* =========================================
   PERIOD BUTTONS
   ========================================= */

document
    .querySelectorAll(".period-btn")
    .forEach(button => {

        button.addEventListener(
            "click",
            function() {

                document
                    .querySelectorAll(".period-btn")
                    .forEach(btn => {
                        btn.classList.remove("active");
                    });

                this.classList.add("active");

                const period =
                    this.dataset.period;

                updateChart(period);
            }
        );

    });

</script>

</body>

</html>

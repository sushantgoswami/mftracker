<?php

$id = (string) $_GET['id'];
$username_user = $_SESSION['username'];
$fundisincode = $id;

$filename = "../cache/nav/$fundisincode.nav.txt";
$data1 = [];
if (($handle = fopen($filename, "r")) !== false) {
     while (($row = fgetcsv($handle, 0, ';')) !== false) {
        $data1[] = [
            "date" => $row[count($row) - 1],
            "navdata" => $row[count($row) - 2]
        ];
    }
    fclose($handle);
}

if (count($data1) > 30) {
    $data1 = array_slice($data1, -30);
}

// echo "-                                                                                                                       -";
?>

<html>

<head>
 <link rel="stylesheet" href="css/fund_chart5.css">
 <script src="charts/js/chart.js"></script>
</head>

<body>

<div class="investment-dashboard">
    <!-- VALUE CHART -->
    <div class="chart-panel">
        <div class="panel-header">
            <div>
                <div class="panel-title">
                    <?php echo $id; ?>
                </div>
                <div class="panel-subtitle">
                    NAV Data Graph
                </div>
            </div>
            <!-- PERIOD BUTTONS -->
             <div class="period-buttons">
             <button type="button" class="period-btn active" data-period="1M">1M</button>
             <button type="button" class="period-btn" data-period="3m">3M</button>
             <button type="button" class="period-btn" data-period="6m">6M</button>
             <button type="button" class="period-btn" data-period="1y">1Y</button>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="myChart5"></canvas>
        </div>
    </div>
</div>

<script>

(function () {

    const chartData = <?= json_encode($data1) ?>;
    console.log("NAV data:", chartData);
    const canvas = document.getElementById("myChart5");
    if (!canvas) {
        console.error("myChart5 canvas not found");
        return;
    }

    if (typeof Chart === "undefined") {
        console.error("Chart.js is not loaded");
        return;
    }

    const labels = chartData.map(function (row) {
        return row.date;
    });

    const navValues = chartData.map(function (row) {
        return Number(row.navdata);
    });

    const ctx = canvas.getContext("2d");

    // Destroy previous chart if it exists
    if (window.myFundChart5) {
        window.myFundChart5.destroy();
    }

    // Create gradient
    const gradient =
        ctx.createLinearGradient(0, 0, 0, 310);

    gradient.addColorStop(0,"rgba(33,150,243,.30)");
    gradient.addColorStop(1,"rgba(33,150,243,.01)");

    // Create chart
    window.myFundChart5 = new Chart(ctx, {

        type: "line",
        data: {
            labels: labels,
            datasets: [
                {
                    label: "NAV Value",
                    data: navValues,
                    borderColor: "#2196f3",
                    backgroundColor: gradient,
                    borderWidth: 3,
                    fill: true,
                    tension: 0.42,
                    pointRadius: 2,
                    pointHoverRadius: 7,
                    pointBackgroundColor: "#ffffff",
                    pointBorderColor: "#2196f3",
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
                    backgroundColor: "rgba(17,24,39,.94)",
                    titleColor: "#ffffff",
                    bodyColor: "#e5e7eb",
                    padding: 13,
                    cornerRadius: 10,
                    displayColors: true,

                    callbacks: {
                        label: function (context) {
                            return (
                                context.dataset.label +
                                ": ₹" +
                                Number(context.raw)
                                    .toLocaleString("en-IN")
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
                        maxRotation: 0
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
                        callback: function (value) {
                            return "₹" +
                                Number(value)
                                    .toLocaleString("en-IN");
                        }
                    }
                }
            }
        }
    });

    console.log(
        "Fund chart created successfully"
    );


    // Resize after modal is visible
    setTimeout(function () {

        if (window.myFundChart5) {
            window.myFundChart5.resize();
        }
    }, 300);


})();

</script>

</body>

</html>

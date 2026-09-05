<?php
session_start();
if (!isset($_SESSION['username'])) {
    http_response_code(403);
    exit('Access denied');
}
$username_user = $_SESSION['username'];
$filename = "cache/totalvalue/$username_user.csv";
$data = [];
if (($handle = fopen($filename, "r")) !== false) {
    while (($row = fgetcsv($handle)) !== false) {
        $data[] = [
            "date"     => $row[0],
            "purchase" => (float)$row[1],
            "current"  => (float)$row[2],
            "gainloss" => (float)$row[3]
        ];
    }
    fclose($handle);
}

/*
 * Read alternate records from bottom.
 * Remove this section if you want every CSV record.
 */
// $data = array_reverse($data);
// $alternate = [];
// foreach ($data as $index => $row) {
//     if ($index % 2 === 0) {
//         $alternate[] = $row;
//     }
// }
// $data = array_reverse($alternate);
/*
 * Calculate summary values
 */

$latestPurchase = 0;
$latestCurrent = 0;
$latestGainLoss = 0;
$latestPercent = 0;
if (!empty($data)) {
    $latest = end($data);
    $latestPurchase = $latest['purchase'];
    $latestCurrent = $latest['current'];
    $latestGainLoss = $latest['gainloss'];
    if ($latestPurchase > 0) {
        $latestPercent =
            ($latestGainLoss / $latestPurchase) * 100;
    }
}
$gainClass =
    $latestGainLoss >= 0 ? 'profit' : 'loss';
?>

<head>
 <script src="charts/js/chart.js"></script>
</head>

<div class="investment-dashboard">
    <!-- HEADER -->
    <div class="dashboard-header">
        <div>
            <div class="dashboard-title">
                Portfolio Performance
            </div>
            <div class="dashboard-subtitle">
                Investment value over time
            </div>
        </div>
        <div class="live-badge">
            <span></span>
            LIVE
        </div>
    </div>
    <!-- KPI CARDS -->
    <div class="kpi-grid">
        <div class="kpi-card blue">
            <div class="kpi-icon">
                ₹
            </div>
            <div class="kpi-label">
                Invested
            </div>
            <div class="kpi-value">
                ₹<?= number_format($latestPurchase, 2) ?>
            </div>
        </div>
        <div class="kpi-card green">
            <div class="kpi-icon">
                ↗
            </div>
            <div class="kpi-label">
                Current Value
            </div>
            <div class="kpi-value">
                ₹<?= number_format($latestCurrent, 2) ?>
            </div>
        </div>
        <div class="kpi-card <?= $gainClass ?>">
            <div class="kpi-icon">
                <?= $latestGainLoss >= 0 ? '↑' : '↓' ?>
            </div>
            <div class="kpi-label">
                Gain / Loss
            </div>
            <div class="kpi-value">
                <?= $latestGainLoss >= 0 ? '+' : '-' ?>
                ₹<?= number_format(abs($latestGainLoss), 2) ?>
            </div>
            <div class="kpi-percent">
                <?= $latestGainLoss >= 0 ? '+' : '' ?>
                <?= number_format($latestPercent, 2) ?>%
            </div>
        </div>
    </div>
    <!-- VALUE CHART -->
    <div class="chart-panel">
        <div class="panel-header">
            <div>
                <div class="panel-title">
                    Portfolio Value
                </div>
                <div class="panel-subtitle">
                    Purchase vs current market value
                </div>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="myChart"></canvas>
        </div>
    </div>
    <!-- GAIN LOSS CHART -->
    <div class="chart-panel gain-panel">
        <div class="panel-header">
            <div>
                <div class="panel-title">
                    Gain / Loss
                </div>
                <div class="panel-subtitle">
                    Portfolio performance
                </div>
            </div>
        </div>
        <div class="chart-container">
            <canvas id="myChart1"></canvas>
        </div>
    </div>
</div>

<style>

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
        linear-gradient(
            145deg,
            #f7f9fc,
            #eef2f7
        );
    padding: 22px;
    border-radius: 20px;
    color: #1c2434;
}
/* =========================================
   HEADER
   ========================================= */
.dashboard-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 22px;
}
.dashboard-title {
    font-size: 24px;
    font-weight: 800;
    letter-spacing: -0.5px;
}
.dashboard-subtitle {
    color: #7b8798;
    font-size: 13px;
    margin-top: 3px;
}
/* LIVE BADGE */
.live-badge {
    background: #e8f8ef;
    color: #198754;
    padding: 7px 12px;
    border-radius: 30px;
    font-size: 11px;
    font-weight: 800;
    letter-spacing: 0.5px;
}
.live-badge span {
    display: inline-block;
    width: 7px;
    height: 7px;
    background: #20c997;
    border-radius: 50%;
    margin-right: 5px;
    box-shadow:
        0 0 0 4px
        rgba(32,201,151,.12);
}

/* =========================================
   KPI GRID
   ========================================= */
.kpi-grid {
    display: grid;
    grid-template-columns:
        repeat(3, 1fr);
    gap: 14px;
    margin-bottom: 18px;
}
.kpi-card {
    position: relative;
    overflow: hidden;
    padding: 17px;
    border-radius: 15px;
    background: #ffffff;
    border: 1px solid
        rgba(0,0,0,.05);
    box-shadow:
        0 8px 25px
        rgba(31,45,61,.07);
    transition:
        transform .2s ease,
        box-shadow .2s ease;
}
.kpi-card:hover {
    transform:
        translateY(-3px);
    box-shadow:
        0 14px 30px
        rgba(31,45,61,.12);
}
/* colored top line */
.kpi-card::before {
    content: "";
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
    height: 3px;
}
.kpi-card.blue::before {
    background: #2196f3;
}
.kpi-card.green::before {
    background: #20c997;
}
.kpi-card.profit::before {
    background: #198754;
}
.kpi-card.loss::before {
    background: #dc3545;
}
.kpi-icon {
    width: 34px;
    height: 34px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 10px;
    background: #f1f5f9;
    font-weight: 800;
    margin-bottom: 9px;
}
.kpi-label {
    color: #8994a4;
    font-size: 12px;
}
.kpi-value {
    font-size: 19px;
    font-weight: 800;
    margin-top: 3px;
}
.kpi-percent {
    font-size: 12px;
    font-weight: 700;
    margin-top: 2px;
}
.profit .kpi-percent {
    color: #198754;
}
.loss .kpi-percent {
    color: #dc3545;
}

/* =========================================
   CHART PANEL
   ========================================= */
.chart-panel {
    background: #ffffff;
    border-radius: 17px;
    padding: 20px;
    margin-bottom: 18px;
    border: 1px solid
        rgba(0,0,0,.04);
    box-shadow:
        0 8px 30px
        rgba(31,45,61,.07);
}
.panel-header {
    display: flex;
    justify-content:
        space-between;
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
    .dashboard-title {
        font-size: 19px;
    }
    .kpi-grid {
        grid-template-columns: 1fr;
    }
    .chart-container {
        height: 270px;
    }
}
</style>

<script>
const chartData = <?= json_encode($data) ?>;

/* =========================================
   DATA
   ========================================= */
const labels = chartData.map(row => row.date);
const purchase = chartData.map(row => Number(row.purchase));
const current = chartData.map(row => Number(row.current));
const gainloss = chartData.map(row => Number(row.gainloss));

/* =========================================
   CHART 1
   PORTFOLIO VALUE
   ========================================= */
const canvas = document.getElementById("myChart");
const ctx = canvas.getContext("2d");

/* Purchase gradient */
const purchaseGradient = ctx.createLinearGradient(0,0,0,310);
purchaseGradient.addColorStop(0,"rgba(33,150,243,.30)");
purchaseGradient.addColorStop(1,"rgba(33,150,243,.01)");

/* Current gradient */
const currentGradient = ctx.createLinearGradient(0,0,0,310);
currentGradient.addColorStop(0,"rgba(32,201,151,.30)");
currentGradient.addColorStop(1,"rgba(32,201,151,.01)");

new Chart(canvas, {
    type: "line",
    data: {
        labels: labels,
        datasets: [
            {
                label:
                    "Purchase Value",
                data:
                    purchase,
                borderColor:
                    "#2196f3",
                backgroundColor:
                    purchaseGradient,
                borderWidth: 3,
                fill: true,
                tension: .42,
                pointRadius: 2,
                pointHoverRadius: 7,
                pointBackgroundColor:
                    "#ffffff",
                pointBorderColor:
                    "#2196f3",
                pointBorderWidth: 2
            },
            {
                label:
                    "Current Value",
                data:
                    current,
                borderColor:
                    "#20c997",
                backgroundColor:
                    currentGradient,
                borderWidth: 3,
                fill: true,
                tension: .42,
                pointRadius: 2,
                pointHoverRadius: 7,
                pointBackgroundColor:
                    "#ffffff",
                pointBorderColor:
                    "#20c997",
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
                titleColor:
                    "#ffffff",
                bodyColor:
                    "#e5e7eb",
                padding: 13,
                cornerRadius: 10,
                displayColors: true,
                callbacks: {
                    label:
                        function(context) {
                            return (
                                context.dataset.label +
                                ": ₹" +
                                Number(
                                    context.raw
                                ).toLocaleString(
                                    "en-IN"
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
                    callback:
                        function(value) {
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
   CHART 2
   GAIN / LOSS
   ========================================= */
const canvas2 = document.getElementById("myChart1");
const ctx2 = canvas2.getContext("2d");
/* Zero line plugin */
const zeroLinePlugin = {id: "zeroLine", afterDraw(chart) {
        const yScale = chart.scales.y;
        const y = yScale.getPixelForValue(0);
        const ctx = chart.ctx;
        ctx.save();
        ctx.beginPath();
        ctx.moveTo(
            chart.chartArea.left,
            y
        );
        ctx.lineTo(
            chart.chartArea.right,
            y
        );
        ctx.lineWidth = 2;
        ctx.strokeStyle =
            "rgba(100,116,139,.35)";
        ctx.setLineDash([5,5]);
        ctx.stroke();
        ctx.restore();
    }
};

new Chart(canvas2, {
    type: "line",
    plugins: [zeroLinePlugin],
    data: {
        labels: labels,
        datasets: [
            {
                label:
                    "Gain / Loss",
                data:
                    gainloss,
                borderWidth: 3,
                tension: .42,
                fill: false,
                pointRadius: 3,
                pointHoverRadius: 8,
                pointBackgroundColor:
                    "#ffffff",
                pointBorderWidth: 2,
                segment: {
                    borderColor:
                        function(segment) {
                            const y1 =
                                segment.p0.parsed.y;
                            const y2 =
                                segment.p1.parsed.y;
                            if (
                                y1 >= 0 &&
                                y2 >= 0
                            ) {
                                return "#198754";
                            }
                            if (
                                y1 < 0 &&
                                y2 < 0
                            ) {
                                return "#dc3545";
                            }
                            return "#f59e0b";
                        }
                },
                pointBorderColor:
                    function(context) {
                        const value =
                            context.raw;
                        return value >= 0
                            ? "#198754"
                            : "#dc3545";
                    }
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
                    padding: 18
                }
            },
            tooltip: {
                backgroundColor:
                    "rgba(17,24,39,.94)",
                padding: 13,
                cornerRadius: 10,
                callbacks: {
                    label:
                        function(context) {
                            const value =
                                Number(
                                    context.raw
                                );
                            const sign =
                                value >= 0
                                    ? "+"
                                    : "-";
                            return (
                                "Gain / Loss: " +
                                sign +
                                "₹" +
                                Math.abs(value)
                                    .toLocaleString(
                                        "en-IN"
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
                    color:
                        "#8994a4",
                    maxRotation: 0
                }
            },
            y: {
                grid: {
                    color:
                        "rgba(148,163,184,.12)"
                },
                ticks: {
                    color:
                        "#8994a4",
                    callback:
                        function(value) {
                            const sign =
                                value >= 0
                                    ? "+"
                                    : "-";
                            return sign +
                                "₹" +
                                Math.abs(value)
                                    .toLocaleString(
                                        "en-IN"
                                    );
                        }
                }
            }
        }
    }
});
</script>

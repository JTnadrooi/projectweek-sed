const xyValues = [
  {x:50, y:7},
  {x:60, y:8},
  {x:70, y:8},
  {x:80, y:9},
  {x:90, y:9},
  {x:100, y:9},
  {x:110, y:10},
  {x:120, y:11},
  {x:130, y:14},
  {x:140, y:14},
  {x:150, y:15}
];

window.currentChartType = "bar";
window.chartTypes = [
    "bar",
    "line",      
    "doughnut",
    "pie",
    "radar",
    "polarArea"
];

function renderChart(canvasId, chartData, layout) {
    const canvas = document.getElementById(canvasId);
    canvas.width = 800;
    canvas.height = 400;

    const labels = chartData.map(entry => entry.usage_date);
    const totalEnergy = chartData.map(entry => parseFloat(entry.total_energy_kwh));
    const peakUsage = chartData.map(entry => parseFloat(entry.peak_usage_kwh));

    if (window.myChartInstance) {
        window.myChartInstance.destroy();
    }

    let datasets;
    if (["doughnut", "pie", "polarArea"].includes(window.currentChartType)) {
        datasets = [
            {
                label: "Total Energy (kWh)",
                data: totalEnergy,
                backgroundColor: [
                    "rgba(0,123,255,0.5)",
                    "rgba(255,99,132,0.5)",
                    "rgba(255,206,86,0.5)",
                    "rgba(75,192,192,0.5)",
                    "rgba(153,102,255,0.5)",
                    "rgba(255,159,64,0.5)"
                ]
            }
        ];
    } else if (window.currentChartType === "radar") {
        datasets = [
            {
                label: "Total Energy (kWh)",
                data: totalEnergy,
                borderColor: "rgba(0,123,255,1)",
                backgroundColor: "rgba(0,123,255,0.2)",
                fill: true
            }
        ];
        if (layout !== 2) {
            datasets.push({
                label: "Peak Usage (kWh)",
                data: peakUsage,
                borderColor: "rgba(255,99,132,1)",
                backgroundColor: "rgba(255,99,132,0.2)",
                fill: true
            });
        }
    } else if (window.currentChartType === "line") {
        datasets = [
            {
                label: "Total Energy (kWh)",
                data: totalEnergy,
                borderColor: "rgba(0,123,255,1)",
                backgroundColor: "rgba(0,123,255,0.2)",
                fill: false,
                tension: 0.3
            }
        ];
        if (layout !== 2) {
            datasets.push({
                label: "Peak Usage (kWh)",
                data: peakUsage,
                borderColor: "rgba(255,99,132,1)",
                backgroundColor: "rgba(255,99,132,0.2)",
                fill: false,
                tension: 0.3
            });
        }
    } else {
        // bar and other types
        datasets = [
            {
                label: "Total Energy (kWh)",
                data: totalEnergy,
                borderColor: "rgba(0,123,255,1)",
                backgroundColor: "rgba(0,123,255,0.2)",
                fill: false
            }
        ];
        if (layout !== 2) {
            datasets.push({
                label: "Peak Usage (kWh)",
                data: peakUsage,
                borderColor: "rgba(255,99,132,1)",
                backgroundColor: "rgba(255,99,132,0.2)",
                fill: false
            });
        }
    }

    window.myChartInstance = new Chart(document.getElementById(canvasId), {
        type: window.currentChartType,
        data: {
            labels: labels.reverse(),
            datasets: datasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: (
                ["doughnut", "pie", "polarArea"].includes(window.currentChartType)
            ) ? {} : {
                x: { title: { display: true, text: "Date" } },
                y: { title: { display: true, text: "kWh" } }
            }
        }
    });
}

if (window.energyData && typeof userLayout !== "undefined") {
    renderChart("energyChart", window.energyData, window.userLayout);
}

document.addEventListener("DOMContentLoaded", function() {
    const chartTypeSelect = document.getElementById("chartType");
    if (chartTypeSelect) {
        chartTypeSelect.value = window.currentChartType;
        chartTypeSelect.addEventListener("change", function() {
            window.currentChartType = this.value;
            renderChart("energyChart", window.energyData, window.userLayout);
        });
    }
});
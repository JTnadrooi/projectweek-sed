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

window.currentChartType = "line";
window.chartTypes = ["line", "bar", "scatter"];

function renderChart(canvasId, chartData, layout) {
    const labels = chartData.map(entry => entry.usage_date);
    const totalEnergy = chartData.map(entry => parseFloat(entry.total_energy_kwh));
    const peakUsage = chartData.map(entry => parseFloat(entry.peak_usage_kwh));

    if (window.myChartInstance) {
        window.myChartInstance.destroy();
    }

    let datasets = [
        {
            label: "Total Energy (kWh)",
            data: window.currentChartType === "scatter"
                ? chartData.map(entry => ({x: entry.usage_date, y: parseFloat(entry.total_energy_kwh)}))
                : totalEnergy,
            borderColor: "rgba(0,123,255,1)",
            backgroundColor: "rgba(0,123,255,0.2)",
            fill: false,
            showLine: window.currentChartType === "scatter" ? false : true
        }
    ];
    if (layout !== 2) {
        datasets.push({
            label: "Peak Usage (kWh)",
            data: window.currentChartType === "scatter"
                ? chartData.map(entry => ({x: entry.usage_date, y: parseFloat(entry.peak_usage_kwh)}))
                : peakUsage,
            borderColor: "rgba(255,99,132,1)",
            backgroundColor: "rgba(255,99,132,0.2)",
            fill: false,
            showLine: window.currentChartType === "scatter" ? false : true
        });
    }

    window.myChartInstance = new Chart(document.getElementById(canvasId), {
        type: window.currentChartType,
        data: {
            labels: window.currentChartType === "scatter" ? undefined : labels.reverse(),
            datasets: datasets
        },
        options: {
            responsive: true,
            plugins: {
                legend: { display: true }
            },
            scales: {
                x: window.currentChartType === "scatter"
                    ? { type: "category", title: { display: true, text: "Date" } }
                    : { title: { display: true, text: "Date" } },
                y: { title: { display: true, text: "kWh" } }
            }
        }
    });
}

if (window.energyData && typeof userLayout !== "undefined") {
    renderChart("energyChart", window.energyData, window.userLayout);
}

document.addEventListener("DOMContentLoaded", function() {
    const btn = document.getElementById("switchChartType");
    if (btn) {
        btn.addEventListener("click", function() {
            // Cycle through chart types: line -> bar -> scatter -> line ...
            const idx = window.chartTypes.indexOf(window.currentChartType);
            window.currentChartType = window.chartTypes[(idx + 1) % window.chartTypes.length];
            renderChart("energyChart", window.energyData, window.userLayout);
        });
    }
});
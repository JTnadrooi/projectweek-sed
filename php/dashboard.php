<?php

declare(strict_types=1);

session_start();
if (empty($_SESSION['username'])) {
    header('Location: auth.php');
    exit;
}

require 'config.php';

// process layout change.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['layout'])) {
    $newLayout = (int)$_POST['layout'];
    $stmt = $pdo->prepare("UPDATE users SET layout = :layout WHERE username = :username");
    $stmt->execute([
        'layout' => $newLayout,
        'username' => $_SESSION['username']
    ]);
    $layout = $newLayout;
} else {
    // fetch updated layout.
    $stmt = $pdo->prepare("SELECT layout FROM users WHERE username = :username");
    $stmt->execute(['username' => $_SESSION['username']]);
    $user = $stmt->fetch();
    $layout = $user['layout'] ?? 0;
}

// fetch energy data.
$energyData = $pdo->query("SELECT usage_date, total_energy_kwh, peak_usage_kwh FROM energy_usage ORDER BY usage_date DESC")->fetchAll();

// compute widgets data
$totalDays = count($energyData);
$sumTotal = $sumPeak = 0;
foreach ($energyData as $entry) {
    $sumTotal += (float)$entry['total_energy_kwh'];
    $sumPeak += (float)$entry['peak_usage_kwh'];
}
$avgTotal = $totalDays ? $sumTotal / $totalDays : 0;
$avgPeak = $totalDays ? $sumPeak / $totalDays : 0;
$peaks = array_map('floatval', array_column($energyData, 'peak_usage_kwh'));
$highestPeak = $peaks ? max($peaks) : 0;
$lowestPeak  = $peaks ? min($peaks) : 0;
$latest      = $energyData[0] ?? ['usage_date' => '', 'total_energy_kwh' => 0, 'peak_usage_kwh' => 0];
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Energy Usage Dashboard</title>
    <link rel="stylesheet" href="../css/dashboard.css">
</head>

<body>
    <div class="dashboard layout-<?= htmlspecialchars((string)$layout) ?>">
        <header>
            <h1>Dashboard</h1>
            <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></p>
            <p><a id="logout" href="logout.php">Logout</a></p>
        </header>

        <section>
            <h3>Current Layout: <?= htmlspecialchars((string)$layout) ?></h3>
            <form method="post">
                <label for="layout">Change Layout:</label>
                <select name="layout" id="layout">
                    <option value="0" <?= $layout == 0 ? 'selected' : '' ?>>Layout 0 (Stacked)</option>
                    <option value="1" <?= $layout == 1 ? 'selected' : '' ?>>Layout 1 (Side by Side)</option>
                    <option value="2" <?= $layout == 2 ? 'selected' : '' ?>>Layout 2 (Widgets Top)</option>
                </select>
                <button type="submit">Update</button>
            </form>
        </section>

        <div class="chart-section">
            <h2>Daily Energy Usage</h2>
            <form id="chartTypeForm" style="margin-bottom:10px;">
                <label for="chartType">Chart Type:</label>
                <select id="chartType" name="chartType">
                    <option value="bar">Bar</option>
                    <option value="line">Line</option>
                    <option value="doughnut">Doughnut</option>
                    <option value="pie">Pie</option>
                    <option value="radar">Radar</option>
                    <option value="polarArea">Polar Area</option>
                </select>
            </form>
            <canvas id="energyChart" width="800" height="400"></canvas>
            <script>
                window.energyData = <?= json_encode($energyData) ?>;
                window.userLayout = <?= json_encode($layout) ?>;
            </script>
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script src="../js/widget.js"></script>
        </div>

        <div class="widgets">
            <div class="widget">
                <h3>Average Usage</h3>
                <p>Total Energy: <?= number_format($avgTotal, 2) ?> kWh</p>
                <p>Peak Usage: <?= number_format($avgPeak, 2) ?> kWh</p>
            </div>
            <div class="widget">
                <h3>Peak Usage Summary</h3>
                <p>Highest Peak: <?= number_format($highestPeak, 2) ?> kWh</p>
                <p>Lowest Peak: <?= number_format($lowestPeak, 2) ?> kWh</p>
            </div>
            <div class="widget">
                <h3>Most Recent Usage (<?= htmlspecialchars($latest['usage_date']) ?>)</h3>
                <p>Total: <?= number_format((float)$latest['total_energy_kwh'], 2) ?> kWh</p>
                <p>Peak: <?= number_format((float)$latest['peak_usage_kwh'], 2) ?> kWh</p>
            </div>
        </div>
    </div>
</body>

</html>
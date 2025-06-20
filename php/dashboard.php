<?php

declare(strict_types=1);

session_start();
if (empty($_SESSION['username'])) {
    header('Location: auth.php');
    exit;
}
require 'config.php';

$stmt = $pdo->query("SELECT usage_date, total_energy_kwh, peak_usage_kwh FROM energy_usage ORDER BY usage_date DESC");
$energyData = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <!-- <link rel="stylesheet" href="../css/dashboard.css"> -->
    <title>Energy Usage Dashboard</title>
    <style>
        .chart-container {
            margin: 20px 0;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <header>
        <h1>Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></p>
        <p><a id="logout" href="logout.php">Logout</a></p>
    </header>

    <article><br><br>
        <h2>Daily Energy Usage</h2>
        <canvas id="energyChart" width="800" height="400"></canvas>
        <div class="chart-container"></div>
        <script>
            const energyData = <?= json_encode($energyData) ?>;
        </script>
        <script src="../js/main.js"></script>
    </article>
</body>

</html>
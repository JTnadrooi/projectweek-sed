<?php

declare(strict_types=1);

session_start();
if (empty($_SESSION['username'])) {
    header('Location: auth.php');
    exit;
}
require 'config.php';

$energyData = $pdo->query("SELECT usage_date, total_energy_kwh, peak_usage_kwh FROM energy_usage ORDER BY usage_date DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <!-- <link rel="stylesheet" href="../css/dashboard.css"> -->
    <title>Energy Usage Dashboard</title>
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
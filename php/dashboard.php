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

<link rel="stylesheet" href="../css/style.css">
<h1>Dashboard</h1>
<p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></p>
<p><a href="logout.php">Logout</a></p>

<h2>Daily Energy Usage</h2>
<table border="1" cellpadding="5" cellspacing="0">
    <tr>
        <th>Date</th>
        <th>Total Energy (kWh)</th>
        <th>Peak Usage (kWh)</th>
    </tr>
    <?php foreach ($energyData as $row): ?>
        <tr>
            <td><?= htmlspecialchars($row['usage_date']) ?></td>
            <td><?= htmlspecialchars($row['total_energy_kwh']) ?></td>
            <td><?= htmlspecialchars($row['peak_usage_kwh']) ?></td>
        </tr>
    <?php endforeach; ?>
</table>
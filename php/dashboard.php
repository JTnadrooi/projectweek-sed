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
}

// fetch updated layout.
$stmt = $pdo->prepare("SELECT layout FROM users WHERE username = :username");
$stmt->execute(['username' => $_SESSION['username']]);
$user = $stmt->fetch();
$layout = $user['layout'] ?? 0;

// fetch energy data.
$energyData = $pdo->query("SELECT usage_date, total_energy_kwh, peak_usage_kwh FROM energy_usage ORDER BY usage_date DESC")->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <title>Energy Usage Dashboard</title>
</head>

<body>
    <header>
        <h1>Dashboard</h1>
        <p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></p>
        <p><a id="logout" href="logout.php">Logout</a></p>
    </header>

    <section>
        <h3>Current Layout: <?= htmlspecialchars((string) $layout) ?></h3>

        <form method="post">
            <label for="layout">Change Layout:</label>
            <select name="layout" id="layout">
                <option value="0" <?= $layout == 0 ? 'selected' : '' ?>>Layout 0 (Default)</option>
                <option value="1" <?= $layout == 1 ? 'selected' : '' ?>>Layout 1</option>
                <option value="2" <?= $layout == 2 ? 'selected' : '' ?>>Layout 2</option>
            </select>
            <button type="submit">Update</button>
        </form>
    </section>

    <article><br><br>
        <h2>Daily Energy Usage</h2>
        <canvas id="energyChart" width="800" height="400"></canvas>
        <div class="chart-container"></div>
        <script>
            const energyData = <?= json_encode($energyData) ?>;
            const userLayout = <?= json_encode($layout) ?>;
        </script>
        <script src="../js/main.js"></script>
    </article>
</body>

</html>
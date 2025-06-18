<?php

declare(strict_types=1);

session_start();
if (empty($_SESSION['username'])) {
    header('Location: auth.php');
    exit;
}
?>

<h1>Dashboard</h1>
<p>Welcome, <?= htmlspecialchars($_SESSION['username']) ?></p>
<p><a href="logout.php">Logout</a></p>
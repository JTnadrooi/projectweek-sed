<?php
session_start();
require 'config.php';

$action = $_GET['action'] ?? 'login';
$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $u = trim($_POST['username'] ?? '');
    $p = $_POST['password'] ?? '';
    if (!$u || !$p) {
        $msg = 'All fields required.';
    } elseif ($action === 'register') {
        try {
            $stmt = $pdo->prepare('INSERT INTO users (username,password) VALUES (?,?)');
            $stmt->execute([$u, $p]);
            $msg = 'Registered! Please login.';
            $action = 'login';
        } catch (PDOException $e) {
            if ($e->getCode() === '23000') {
                $msg = 'Username already exists.';
            } else {
                $msg = 'Registration error.';
            }
        }
    } else {
        $stmt = $pdo->prepare('SELECT username,password FROM users WHERE username=?');
        $stmt->execute([$u]);
        $user = $stmt->fetch();
        if ($user && $p === $user['password']) {
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        } else {
            $msg = 'Invalid login.';
        }
    }
}
?>

<h1><?= ucfirst($action) ?></h1>
<?= $msg ? "<p>$msg</p>" : '' ?>
<form method="post">
    <input name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button><?= ucfirst($action) ?></button>
</form>
<p>
    <?php if ($action === 'login'): ?>
        <a href="?action=register">Register</a>
    <?php else: ?>
        <a href="?action=login">Login</a>
    <?php endif; ?>
</p>
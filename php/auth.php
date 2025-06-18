<?php
session_start();
require 'config.php';

$mode = $_GET['action'] ?? 'login';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) $message = 'All fields required.';
    elseif ($mode === 'register') {
        try {
            $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)')->execute([$username, $password]);
            $message = 'Registered! Please login.';
            $mode = 'login';
        } catch (PDOException $e) {
            $message = $e->getCode() === '23000' ? 'Username already exists.' : 'Registration error.';
        }
    } else {
        $stmt = $pdo->prepare('SELECT username, password FROM users WHERE username = ?');
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && $password === $user['password']) {
            $_SESSION['username'] = $user['username'];
            header('Location: dashboard.php');
            exit;
        } else $message = 'Invalid login.';
    }
}
?>

<h1><?= ucfirst($mode) ?></h1>
<?= $message ? "<p>$message</p>" : '' ?>
<form method="post">
    <input name="username" placeholder="Username" required><br>
    <input type="password" name="password" placeholder="Password" required><br>
    <button><?= ucfirst($mode) ?></button>
</form>
<p>
    <a href="?action=<?= $mode === 'login' ? 'register' : 'login' ?>">
        <?= $mode === 'login' ? 'Register' : 'Login' ?>
    </a>
</p>
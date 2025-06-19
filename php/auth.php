<?php

declare(strict_types=1); // makes type casting more strict.

session_start();
require 'config.php';

$mode = $_GET['action'] ?? 'login';
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? ''); // get variables from form.
    $password = $_POST['password'] ?? '';

    if (!$username || !$password) $message = 'All fields required.';
    elseif ($mode === 'register') { // registering
        try {
            $pdo->prepare('INSERT INTO users (username, password) VALUES (?, ?)')->execute([$username, $password]); // add new user.
            $message = 'Registered! Please login.'; // only shows is above line succeeds.
            $mode = 'login';
        } catch (PDOException $e) { // when INSERT fails.
            $message = $e->getCode() === '23000' ? 'Username already exists.' : 'Registration error.';
        }
    } else { // login.
        $stmt = $pdo->prepare('SELECT username, password FROM users WHERE username = ?'); // fetch user
        $stmt->execute([$username]);
        $user = $stmt->fetch();

        if ($user && $password === $user['password']) { // if password matches and user exists
            $_SESSION['username'] = $user['username']; // store to session.
            header('Location: dashboard.php');
            exit;
        } else $message = 'Invalid login.';
    }
}
?>
<link rel="stylesheet" href="../css/style.css">
<div>
<h1><?= ucfirst($mode) ?></h1>
<?= $message ? "<p>$message</p>" : '' ?>
<form method="post">
    <input name="username" placeholder="Username" required><br><br>
    <input type="password" name="password" placeholder="Password" required><br><br>
    <button><?= ucfirst($mode) ?></button>
</form>
<p>
    <a href="?action=<?= $mode === 'login' ? 'register' : 'login' ?>">
        <?= $mode === 'login' ? 'No account yet? Click here!' : 'Already have an account? Click here!' ?>
    </a>
</p>
</div>
<img src="../img/green_energy.jpg">
<?php

declare(strict_types=1);

$pdo = new PDO(
    'mysql:host=localhost;dbname=app_db;charset=utf8mb4',
    'your_db_user',
    'your_db_pass',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

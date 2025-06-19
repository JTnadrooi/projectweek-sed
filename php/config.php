<?php

declare(strict_types=1);

try {
    $pdo = new PDO(
        'mysql:host=localhost;dbname=sed-projectweek;charset=utf8mb4',
        'root',
        '',
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

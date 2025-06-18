<?php

declare(strict_types=1);

$pdo = new PDO(
    'mysql:host=localhost;dbname=projectweek-sed;charset=utf8mb4',
    'root',
    '',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);

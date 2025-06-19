CREATE DATABASE IF NOT EXISTS `sed-projectweek`;

USE `sed-projectweek`;

CREATE TABLE
    IF NOT EXISTS `users` (
        `id` INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        `username` VARCHAR(50) NOT NULL UNIQUE,
        `password` VARCHAR(50) NOT NULL
    ) ENGINE = InnoDB;

INSERT INTO
    `users` (`username`, `password`)
VALUES
    ('test', 'pass');

CREATE TABLE
    energy_usage (
        usage_date DATE PRIMARY KEY,
        total_energy_kwh DECIMAL(10, 2),
        peak_usage_kwh DECIMAL(10, 2)
    );

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

INSERT INTO
    energy_usage (usage_date, total_energy_kwh, peak_usage_kwh)
VALUES
    ('2025-06-01', 120.5, 60.2),
    ('2025-06-02', 115.3, 58.0),
    ('2025-06-03', 122.1, 61.5),
    ('2025-06-04', 118.9, 59.3),
    ('2025-06-05', 121.0, 60.1),
    ('2025-06-06', 117.6, 57.8),
    ('2025-06-07', 119.4, 58.9),
    ('2025-06-08', 123.0, 62.0),
    ('2025-06-09', 116.5, 56.7),
    ('2025-06-10', 118.2, 58.1),
    ('2025-06-11', 120.9, 60.0),
    ('2025-06-12', 122.4, 61.2),
    ('2025-06-13', 119.7, 59.0),
    ('2025-06-14', 117.8, 58.3),
    ('2025-06-15', 121.5, 60.5),
    ('2025-06-16', 118.6, 57.9),
    ('2025-06-17', 123.3, 62.1),
    ('2025-06-18', 120.0, 59.7),
    ('2025-06-19', 119.0, 58.8),
    ('2025-06-20', 122.6, 61.0);
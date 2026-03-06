<?php
declare(strict_types=1);

session_start();

$host = 'localhost';
$dbName = 'skyhub_db';
$username = 'root';
$password = '';

try {
    $pdo = new PDO(
        "mysql:host={$host};dbname={$dbName};charset=utf8mb4",
        $username,
        $password,
        [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]
    );
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

if (!function_exists('ensureEvaluationsTable')) {
    function ensureEvaluationsTable(PDO $pdo): void
    {
        $pdo->exec(
            "CREATE TABLE IF NOT EXISTS evaluations (
                evaluation_id INT AUTO_INCREMENT PRIMARY KEY,
                user_id INT NULL,
                project_id INT NULL,
                usability_score TINYINT NOT NULL,
                performance_score TINYINT NOT NULL,
                design_score TINYINT NOT NULL,
                support_score TINYINT NOT NULL,
                overall_score TINYINT NOT NULL,
                recommendation ENUM('yes', 'maybe', 'no') NOT NULL DEFAULT 'yes',
                comment TEXT,
                created_at DATETIME NOT NULL,
                CONSTRAINT fk_evaluations_user
                    FOREIGN KEY (user_id) REFERENCES users(user_id)
                    ON DELETE SET NULL,
                CONSTRAINT fk_evaluations_project
                    FOREIGN KEY (project_id) REFERENCES projects(project_id)
                    ON DELETE SET NULL
            )"
        );
    }
}

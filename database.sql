CREATE DATABASE IF NOT EXISTS skyhub_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE skyhub_db;

CREATE TABLE IF NOT EXISTS users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    role VARCHAR(20) NOT NULL DEFAULT 'client',
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS admins (
    admin_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    email VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS projects (
    project_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    description TEXT NOT NULL,
    start_date DATE,
    end_date DATE
);

CREATE TABLE IF NOT EXISTS team (
    team_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    role VARCHAR(30) NOT NULL,
    project_id INT NULL,
    CONSTRAINT fk_team_project
        FOREIGN KEY (project_id) REFERENCES projects(project_id)
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS drones (
    drone_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(50) NOT NULL,
    type VARCHAR(30) NOT NULL,
    status VARCHAR(20) NOT NULL,
    specs TEXT
);

CREATE TABLE IF NOT EXISTS feedback (
    feedback_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NULL,
    project_id INT NULL,
    message TEXT NOT NULL,
    date DATE NOT NULL,
    CONSTRAINT fk_feedback_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE SET NULL,
    CONSTRAINT fk_feedback_project
        FOREIGN KEY (project_id) REFERENCES projects(project_id)
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS evaluations (
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
    CONSTRAINT chk_evaluations_usability CHECK (usability_score BETWEEN 1 AND 5),
    CONSTRAINT chk_evaluations_performance CHECK (performance_score BETWEEN 1 AND 5),
    CONSTRAINT chk_evaluations_design CHECK (design_score BETWEEN 1 AND 5),
    CONSTRAINT chk_evaluations_support CHECK (support_score BETWEEN 1 AND 5),
    CONSTRAINT chk_evaluations_overall CHECK (overall_score BETWEEN 1 AND 5),
    CONSTRAINT fk_evaluations_user
        FOREIGN KEY (user_id) REFERENCES users(user_id)
        ON DELETE SET NULL,
    CONSTRAINT fk_evaluations_project
        FOREIGN KEY (project_id) REFERENCES projects(project_id)
        ON DELETE SET NULL
);

CREATE TABLE IF NOT EXISTS site_media (
    media_key VARCHAR(80) PRIMARY KEY,
    media_url TEXT NOT NULL,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO projects (name, description, start_date, end_date) VALUES
('Drone Mapping', 'Mapping city parks with aerial scanning', '2025-11-01', '2025-12-31'),
('Media Shoot', 'Filming promotional aerospace video', '2025-10-11', '2025-12-22'),
('R&D Testing', 'Testing new drone configurations and firmware', '2025-09-05', '2025-12-23');

INSERT INTO team (name, role, project_id) VALUES
('Your Name', 'CEO & Lead Developer', 1);

INSERT INTO drones (name, type, status, specs) VALUES
('SkyFalcon X1', 'Quadcopter', 'Inactive', 'HD cam, 90min flight'),
('AeroWing Z2', 'Hexacopter', 'Maintenance', '4K cam, 20min flight'),
('EagleEye S4', 'Hexacopter', 'Active', 'HD cam, 10min flight');

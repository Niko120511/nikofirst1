<?php
declare(strict_types=1);

function isLoggedIn(): bool
{
    return isset($_SESSION['user_id'], $_SESSION['user_name']);
}

function requireLogin(): void
{
    if (!isLoggedIn()) {
        header('Location: login.php');
        exit;
    }
}

function isAdmin(): bool
{
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireAdmin(): void
{
    if (!isAdmin()) {
        header('Location: dashboard.php');
        exit;
    }
}

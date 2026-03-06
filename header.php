<?php
declare(strict_types=1);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SkyHub Technologies</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Grotesk:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="skyhub.css?v=<?php echo filemtime(__DIR__ . '/skyhub.css'); ?>">
</head>
<body>
<header class="site-header">
    <div class="container nav-wrap">
        <a class="logo" href="index.php">SkyHub</a>
        <nav class="nav">
            <a href="index.php">Home</a>
            <a href="projects.php">Projects</a>
            <a href="evaluation.php">Evaluation</a>
            <a href="gallery.php">Gallery</a>
            <a href="team-skyhub.php">Team</a>
            <a href="contact.php">Contact</a>
            <?php if (!empty($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Dashboard</a>
                <a href="profile.php">Profile</a>
                <?php if (!empty($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin'): ?>
                    <a href="adminpage.php">Admin</a>
                <?php endif; ?>
                <a href="logout.php" class="btn-small">Logout</a>
            <?php else: ?>
                <a href="login.php">Login</a>
                <a href="register.php" class="btn-small">Register</a>
            <?php endif; ?>
        </nav>
    </div>
</header>
<main>



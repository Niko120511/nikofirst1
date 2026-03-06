<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
requireLogin();
ensureEvaluationsTable($pdo);

$projectCount = (int)$pdo->query('SELECT COUNT(*) FROM projects')->fetchColumn();
$teamCount = (int)$pdo->query('SELECT COUNT(*) FROM team')->fetchColumn();
$droneCount = (int)$pdo->query('SELECT COUNT(*) FROM drones')->fetchColumn();
$evaluationCount = (int)$pdo->query('SELECT COUNT(*) FROM evaluations')->fetchColumn();

require_once __DIR__ . '/header.php';
?>
<section class="container">
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['user_name']); ?></h1>
    <p>Your PHP session is active. This page is only visible to logged-in users.</p>
</section>

<section class="container grid-4">
    <article class="card stat-card">
        <p>Total Projects</p>
        <h3><?php echo $projectCount; ?></h3>
    </article>
    <article class="card stat-card">
        <p>Team Members</p>
        <h3><?php echo $teamCount; ?></h3>
    </article>
    <article class="card stat-card">
        <p>Drones</p>
        <h3><?php echo $droneCount; ?></h3>
    </article>
    <article class="card stat-card">
        <p>Evaluation Entries</p>
        <h3><?php echo $evaluationCount; ?></h3>
    </article>
</section>
<?php require_once __DIR__ . '/footer.php'; ?>

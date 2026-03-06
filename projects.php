<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/site_media.php';

$projects = $pdo->query('SELECT project_id, name, description, start_date, end_date FROM projects ORDER BY start_date DESC')->fetchAll();
$media = getSiteMediaMap($pdo);
$mediaVersion = (string)time();
$projectImages = [
    (string)$media['projects_1'],
    (string)$media['projects_2'],
    (string)$media['projects_3'],
    (string)$media['projects_4'],
];

require_once __DIR__ . '/header.php';
?>
<section class="container">
    <h1>Projects</h1>
    <p>
        Each project at SkyHub follows a simple cycle: define mission objective,
        test in a controlled environment, measure outcomes, and improve.
    </p>
</section>

<section class="container grid-2">
    <?php foreach ($projects as $index => $project): ?>
        <article class="card">
            <img
                class="card-image"
                src="<?php echo htmlspecialchars($projectImages[$index % count($projectImages)]); ?>&amp;v=<?php echo urlencode($mediaVersion); ?>"
                alt="<?php echo htmlspecialchars($project['name']); ?> project visual"
            >
            <h3><?php echo htmlspecialchars($project['name']); ?></h3>
            <p><?php echo htmlspecialchars($project['description']); ?></p>
            <p><strong>Start:</strong> <?php echo htmlspecialchars($project['start_date']); ?></p>
            <p><strong>End:</strong> <?php echo htmlspecialchars($project['end_date']); ?></p>
            <p><strong>Status:</strong> <?php echo (strtotime((string)$project['end_date']) >= time()) ? 'Planned / Active' : 'Completed'; ?></p>
        </article>
    <?php endforeach; ?>

    <?php if (!$projects): ?>
        <article class="card">
            <p>No projects available yet.</p>
        </article>
    <?php endif; ?>
</section>
<?php require_once __DIR__ . '/footer.php'; ?>

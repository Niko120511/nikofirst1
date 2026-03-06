<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/site_media.php';

$media = getSiteMediaMap($pdo);
$mediaVersion = (string)time();
require_once __DIR__ . '/header.php';
?>
<section class="container">
    <h1>Gallery</h1>
    <p>
        A visual collection of drone operations, engineering workflow, and aerospace inspiration
        that represents the SkyHub project direction.
    </p>
</section>

<section class="container photo-grid photo-grid-large">
    <img src="<?php echo htmlspecialchars((string)$media['gallery_1']); ?>&amp;v=<?php echo urlencode($mediaVersion); ?>" alt="Drone flying above sea cliffs">
    <img src="<?php echo htmlspecialchars((string)$media['gallery_2']); ?>&amp;v=<?php echo urlencode($mediaVersion); ?>" alt="Technical aerospace dashboard screen">
    <img src="<?php echo htmlspecialchars((string)$media['gallery_3']); ?>&amp;v=<?php echo urlencode($mediaVersion); ?>" alt="Drone pilot controller close-up">
    <img src="<?php echo htmlspecialchars((string)$media['gallery_4']); ?>&amp;v=<?php echo urlencode($mediaVersion); ?>" alt="Aircraft wing in flight">
    <img src="<?php echo htmlspecialchars((string)$media['gallery_5']); ?>&amp;v=<?php echo urlencode($mediaVersion); ?>" alt="Drone on launch platform">
    <img src="<?php echo htmlspecialchars((string)$media['gallery_6']); ?>&amp;v=<?php echo urlencode($mediaVersion); ?>" alt="Earth at night from orbit">
</section>
<?php require_once __DIR__ . '/footer.php'; ?>

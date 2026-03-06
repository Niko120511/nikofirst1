<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/site_media.php';

$media = getSiteMediaMap($pdo);
require_once __DIR__ . '/header.php';
?>
<section class="hero">
    <div class="container split">
        <div>
            <p class="eyebrow">SkyHub Team</p>
            <h1>Small Team. Fast Build Cycles.</h1>
            <p>
                SkyHub runs with a compact execution model: one founder leading product,
                engineering, testing, and deployment with focused iteration.
            </p>
            <div class="hero-actions">
                <a class="btn-primary" href="projects.php">See Active Projects</a>
                <a class="btn-secondary" href="contact.php">Work With SkyHub</a>
            </div>
        </div>
        <figure class="hero-figure card">
            <img src="<?php echo htmlspecialchars((string)$media['team_hero']); ?>" alt="Engineering team workstation">
            <figcaption>Focused development setup for UAV testing and iteration.</figcaption>
        </figure>
    </div>
</section>

<section class="container grid-3">
    <article class="card auth-card">
        <h3>Founder / Lead Engineer</h3>
        <p>Owns architecture, feature delivery, testing standards, and release quality.</p>
    </article>
    <article class="card auth-card">
        <h3>Operations Mode</h3>
        <p>Weekly sprint planning, fast prototyping, and direct customer feedback loops.</p>
    </article>
    <article class="card auth-card">
        <h3>Collaboration</h3>
        <p>Open to pilot programs with schools, labs, and local technical partners.</p>
    </article>
</section>

<section class="container">
    <div class="card timeline">
        <h2>How We Work</h2>
        <p><strong>1. Define Mission:</strong> Problem framing with expected flight outcome.</p>
        <p><strong>2. Build Prototype:</strong> Hardware/software integration for a testable version.</p>
        <p><strong>3. Test & Measure:</strong> Controlled runs with logs, stability checks, and notes.</p>
        <p><strong>4. Improve:</strong> Rapid iteration until mission performance is reliable.</p>
    </div>
</section>
<?php require_once __DIR__ . '/footer.php'; ?>

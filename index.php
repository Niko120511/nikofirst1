<?php
declare(strict_types=1);
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/site_media.php';

$media = getSiteMediaMap($pdo);
$mediaVersion = (string)time();

require_once __DIR__ . '/header.php';
?>
<section class="hero">
    <div class="container split">
        <div>
            <p class="eyebrow">Aerospace Innovation Hub</p>
            <h1>Building Practical UAV Systems for Real-World Missions</h1>
            <p>
                SkyHub Technologies is an independent aerospace venture focused on
                applied drone engineering, aerial mapping, and rapid prototype testing.
                We turn ideas into testable airborne systems with clear technical results.
            </p>
            <div class="hero-actions">
                <a class="btn-primary" href="projects.php">Explore Projects</a>
                <?php if (empty($_SESSION['user_id'])): ?>
                    <a class="btn-secondary" href="register.php">Create Account</a>
                <?php else: ?>
                    <a class="btn-secondary" href="dashboard.php">Open Dashboard</a>
                <?php endif; ?>
            </div>
        </div>
        <figure class="hero-figure card">
            <img src="<?php echo htmlspecialchars((string)$media['home_hero']); ?>&amp;v=<?php echo urlencode($mediaVersion); ?>" alt="Drone flying above mountainous terrain">
            <figcaption>Field flight test, autonomous stabilization mode.</figcaption>
        </figure>
    </div>
</section>

<section class="container grid-4">
    <article class="card stat-card">
        <p>Founder-Led Team</p>
        <h3>1 CEO / Developer</h3>
    </article>
    <article class="card stat-card">
        <p>Active Focus Areas</p>
        <h3>4 Domains</h3>
    </article>
    <article class="card stat-card">
        <p>Prototype Cycles</p>
        <h3>12+ Iterations</h3>
    </article>
    <article class="card stat-card">
        <p>Partner Interest</p>
        <h3>Growing Pipeline</h3>
    </article>
</section>

<section class="container grid-3">
    <article class="card">
        <h3>Mission</h3>
        <p>
            Deliver reliable and understandable aerospace solutions for education,
            research groups, and industrial pilots.
        </p>
    </article>
    <article class="card">
        <h3>Vision</h3>
        <p>
            Become a trusted regional innovation studio where compact UAV systems
            are tested, documented, and shared with full engineering clarity.
        </p>
    </article>
    <article class="card">
        <h3>Operating Principle</h3>
        <p>
            Build fast, test safely, and measure every iteration with transparent
            data before scaling.
        </p>
    </article>
</section>

<section class="container">
    <h2>Core Services</h2>
    <div class="grid-3">
        <article class="card">
            <h3>Aerial Mapping</h3>
            <p>City scanning, land overview, and site reporting with geotagged outputs.</p>
        </article>
        <article class="card">
            <h3>Drone R&D</h3>
            <p>Prototype assembly, firmware checks, and controlled test flights.</p>
        </article>
        <article class="card">
            <h3>Media & Demonstrations</h3>
            <p>Flight showcases, educational demos, and technical presentation support.</p>
        </article>
    </div>
</section>

<section class="container">
    <h2>Recent Visual Highlights</h2>
    <div class="photo-grid">
        <img src="<?php echo htmlspecialchars((string)$media['home_highlight_1']); ?>&amp;v=<?php echo urlencode($mediaVersion); ?>" alt="Drone near sunset over landscape">
        <img src="<?php echo htmlspecialchars((string)$media['home_highlight_2']); ?>&amp;v=<?php echo urlencode($mediaVersion); ?>" alt="Engineer writing technical notes">
        <img src="<?php echo htmlspecialchars((string)$media['home_highlight_3']); ?>&amp;v=<?php echo urlencode($mediaVersion); ?>" alt="Earth and atmosphere from space perspective">
        <img src="<?php echo htmlspecialchars((string)$media['home_highlight_4']); ?>&amp;v=<?php echo urlencode($mediaVersion); ?>" alt="Close-up of drone body and motors">
    </div>
</section>

<section class="container grid-3">
    <article class="card">
        <h3>Projects</h3>
        <p>Detailed engineering portfolio with mission goals, timelines, and measured outcomes.</p>
    </article>
    <article class="card">
        <h3>CEO & Development</h3>
        <p>The platform is designed and maintained by one founder for focused execution.</p>
    </article>
    <article class="card">
        <h3>Contact</h3>
        <p>Use secure forms for proposals, collaboration requests, and technical questions.</p>
    </article>
</section>

<section class="container">
    <div class="card timeline">
        <h2>Roadmap Snapshot</h2>
        <p><strong>Q1:</strong> Website launch, user registration, and centralized portfolio.</p>
        <p><strong>Q2:</strong> Advanced dashboard for flight logs and media tracking.</p>
        <p><strong>Q3:</strong> Partner portal for investor briefings and internship requests.</p>
        <p><strong>Q4:</strong> Expansion to cloud storage and analytics modules.</p>
    </div>
</section>
<section class="container">
    <div class="cta card">
        <h2>Interested in collaboration?</h2>
        <p>Reach SkyHub for pilots, demos, educational workshops, or custom aerospace concepts.</p>
        <a class="btn-primary" href="contact.php">Send a Message</a>
    </div>
</section>
<?php require_once __DIR__ . '/footer.php'; ?>

<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';

requireLogin();
ensureEvaluationsTable($pdo);

$errors = [];
$success = '';

$criteriaDefaults = [
    'usability_score' => '3',
    'performance_score' => '3',
    'design_score' => '3',
    'support_score' => '3',
    'overall_score' => '3',
];

$values = [
    'project_id' => '',
    'recommendation' => 'yes',
    'comment' => '',
] + $criteriaDefaults;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $values['project_id'] = trim((string)($_POST['project_id'] ?? ''));
    $values['recommendation'] = trim((string)($_POST['recommendation'] ?? 'yes'));
    $values['comment'] = trim((string)($_POST['comment'] ?? ''));

    foreach (array_keys($criteriaDefaults) as $field) {
        $values[$field] = trim((string)($_POST[$field] ?? '3'));
    }

    $projectId = (int)$values['project_id'];
    if ($projectId <= 0) {
        $errors[] = 'Please choose a project.';
    }

    $allowedRecommendations = ['yes', 'maybe', 'no'];
    if (!in_array($values['recommendation'], $allowedRecommendations, true)) {
        $errors[] = 'Invalid recommendation option.';
    }

    $scores = [];
    foreach (array_keys($criteriaDefaults) as $field) {
        $score = (int)$values[$field];
        if ($score < 1 || $score > 5) {
            $errors[] = 'All scores must be between 1 and 5.';
            break;
        }
        $scores[$field] = $score;
    }

    if (strlen($values['comment']) > 1200) {
        $errors[] = 'Comment is too long (max 1200 characters).';
    }

    if (!$errors) {
        $projectStmt = $pdo->prepare('SELECT project_id FROM projects WHERE project_id = :id LIMIT 1');
        $projectStmt->execute(['id' => $projectId]);
        if (!$projectStmt->fetch()) {
            $errors[] = 'Selected project does not exist.';
        }
    }

    if (!$errors) {
        $insertStmt = $pdo->prepare(
            'INSERT INTO evaluations (
                user_id,
                project_id,
                usability_score,
                performance_score,
                design_score,
                support_score,
                overall_score,
                recommendation,
                comment,
                created_at
            ) VALUES (
                :user_id,
                :project_id,
                :usability_score,
                :performance_score,
                :design_score,
                :support_score,
                :overall_score,
                :recommendation,
                :comment,
                NOW()
            )'
        );
        $insertStmt->execute([
            'user_id' => (int)$_SESSION['user_id'],
            'project_id' => $projectId,
            'usability_score' => $scores['usability_score'],
            'performance_score' => $scores['performance_score'],
            'design_score' => $scores['design_score'],
            'support_score' => $scores['support_score'],
            'overall_score' => $scores['overall_score'],
            'recommendation' => $values['recommendation'],
            'comment' => $values['comment'],
        ]);

        $success = 'Evaluation submitted successfully.';
        $values = [
            'project_id' => '',
            'recommendation' => 'yes',
            'comment' => '',
        ] + $criteriaDefaults;
    }
}

$projects = $pdo->query('SELECT project_id, name FROM projects ORDER BY name ASC')->fetchAll();
$myEvaluationsStmt = $pdo->prepare(
    'SELECT e.evaluation_id, p.name AS project_name, e.overall_score, e.recommendation, e.created_at
     FROM evaluations e
     LEFT JOIN projects p ON p.project_id = e.project_id
     WHERE e.user_id = :user_id
     ORDER BY e.created_at DESC
     LIMIT 8'
);
$myEvaluationsStmt->execute(['user_id' => (int)$_SESSION['user_id']]);
$myEvaluations = $myEvaluationsStmt->fetchAll();

require_once __DIR__ . '/header.php';
?>
<section class="container evaluation-wrap">
    <h1>3.3 Evaluation</h1>
    <p>Assess project quality and user satisfaction based on core system objectives.</p>
</section>

<section class="container grid-3 evaluation-goals">
    <article class="card eval-goal">
        <h3>Objective 1</h3>
        <p>Improve usability so users can access project information quickly and clearly.</p>
    </article>
    <article class="card eval-goal">
        <h3>Objective 2</h3>
        <p>Deliver stable performance and reliable project updates for everyday usage.</p>
    </article>
    <article class="card eval-goal">
        <h3>Objective 3</h3>
        <p>Increase overall satisfaction with a transparent interface and feedback loop.</p>
    </article>
</section>

<section class="container grid-2 evaluation-grid">
    <article class="card evaluation-form-card">
        <h2>Submit Evaluation</h2>

        <?php if ($success): ?>
            <div class="alert alert-success">
                <p><?php echo htmlspecialchars($success); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($errors): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="form-grid">
            <label for="project_id">Project</label>
            <select id="project_id" name="project_id" required>
                <option value="">Select project</option>
                <?php foreach ($projects as $project): ?>
                    <?php $selected = ((string)$project['project_id'] === $values['project_id']) ? 'selected' : ''; ?>
                    <option value="<?php echo (int)$project['project_id']; ?>" <?php echo $selected; ?>>
                        <?php echo htmlspecialchars((string)$project['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="usability_score">Usability (1-5)</label>
            <input id="usability_score" type="number" name="usability_score" min="1" max="5" value="<?php echo htmlspecialchars($values['usability_score']); ?>" required>

            <label for="performance_score">Performance (1-5)</label>
            <input id="performance_score" type="number" name="performance_score" min="1" max="5" value="<?php echo htmlspecialchars($values['performance_score']); ?>" required>

            <label for="design_score">Design (1-5)</label>
            <input id="design_score" type="number" name="design_score" min="1" max="5" value="<?php echo htmlspecialchars($values['design_score']); ?>" required>

            <label for="support_score">Support/Documentation (1-5)</label>
            <input id="support_score" type="number" name="support_score" min="1" max="5" value="<?php echo htmlspecialchars($values['support_score']); ?>" required>

            <label for="overall_score">Overall Score (1-5)</label>
            <input id="overall_score" type="number" name="overall_score" min="1" max="5" value="<?php echo htmlspecialchars($values['overall_score']); ?>" required>

            <label for="recommendation">Would you recommend this system?</label>
            <select id="recommendation" name="recommendation" required>
                <option value="yes" <?php echo $values['recommendation'] === 'yes' ? 'selected' : ''; ?>>Yes</option>
                <option value="maybe" <?php echo $values['recommendation'] === 'maybe' ? 'selected' : ''; ?>>Maybe</option>
                <option value="no" <?php echo $values['recommendation'] === 'no' ? 'selected' : ''; ?>>No</option>
            </select>

            <label for="comment">Comment (optional)</label>
            <textarea id="comment" name="comment" rows="4" maxlength="1200" placeholder="Write strengths, weaknesses, and suggestions..."><?php echo htmlspecialchars($values['comment']); ?></textarea>

            <button class="btn-primary" type="submit">Send Evaluation</button>
        </form>
    </article>

    <article class="card evaluation-history-card">
        <h2>My Recent Evaluations</h2>
        <div class="admin-table-wrap">
            <table class="admin-table">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>Project</th>
                    <th>Overall</th>
                    <th>Recommend</th>
                    <th>Date</th>
                </tr>
                </thead>
                <tbody>
                <?php if (!$myEvaluations): ?>
                    <tr>
                        <td colspan="5">No evaluations yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($myEvaluations as $evaluation): ?>
                        <tr>
                            <td><?php echo (int)$evaluation['evaluation_id']; ?></td>
                            <td><?php echo htmlspecialchars((string)($evaluation['project_name'] ?? 'N/A')); ?></td>
                            <td><?php echo (int)$evaluation['overall_score']; ?>/5</td>
                            <td><?php echo htmlspecialchars(ucfirst((string)$evaluation['recommendation'])); ?></td>
                            <td><?php echo htmlspecialchars((string)$evaluation['created_at']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
                </tbody>
            </table>
        </div>
    </article>
</section>
<?php require_once __DIR__ . '/footer.php'; ?>

<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$projects = $pdo->query('SELECT project_id, name FROM projects ORDER BY name ASC')->fetchAll();
$message = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $projectId = (int)($_POST['project_id'] ?? 0);
    $feedbackMessage = trim($_POST['message'] ?? '');
    $userId = isset($_SESSION['user_id']) ? (int)$_SESSION['user_id'] : null;

    if ($feedbackMessage === '') {
        $error = 'Please write your message.';
    } else {
        $stmt = $pdo->prepare(
            'INSERT INTO feedback (user_id, project_id, message, date) VALUES (:user_id, :project_id, :message, CURDATE())'
        );
        $stmt->bindValue(':user_id', $userId, $userId === null ? PDO::PARAM_NULL : PDO::PARAM_INT);
        $stmt->bindValue(':project_id', $projectId > 0 ? $projectId : null, $projectId > 0 ? PDO::PARAM_INT : PDO::PARAM_NULL);
        $stmt->bindValue(':message', $feedbackMessage, PDO::PARAM_STR);
        $stmt->execute();

        $message = 'Thank you. Your message was submitted.';
    }
}

require_once __DIR__ . '/header.php';
?>
<section class="container auth-wrap">
    <div class="card auth-card">
        <h2>Contact / Feedback</h2>
        <p>Share inquiries, partnership ideas, or project feedback.</p>

        <?php if ($message): ?>
            <div class="alert alert-success"><p><?php echo htmlspecialchars($message); ?></p></div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error"><p><?php echo htmlspecialchars($error); ?></p></div>
        <?php endif; ?>

        <form method="post" class="form-grid">
            <label for="project_id">Related Project (Optional)</label>
            <select id="project_id" name="project_id">
                <option value="0">General Inquiry</option>
                <?php foreach ($projects as $project): ?>
                    <option value="<?php echo (int)$project['project_id']; ?>">
                        <?php echo htmlspecialchars($project['name']); ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <label for="message">Message</label>
            <textarea id="message" name="message" rows="6" required></textarea>

            <button class="btn-primary" type="submit">Send Message</button>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/footer.php'; ?>


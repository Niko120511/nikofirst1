<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
require_once __DIR__ . '/site_media.php';

requireLogin();
requireAdmin();
ensureEvaluationsTable($pdo);

function normalizeDateInput(string $value): ?string
{
    $value = trim($value);
    return $value === '' ? null : $value;
}

function isValidImageUrl(string $value): bool
{
    if (!filter_var($value, FILTER_VALIDATE_URL)) {
        return false;
    }
    return (bool)preg_match('/\.(jpg|jpeg|png|webp|gif)(\?.*)?$/i', $value) || str_contains($value, 'unsplash.com/');
}

$message = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = (string)($_POST['action'] ?? '');

    try {
        if ($action === 'project_add') {
            $name = trim((string)($_POST['name'] ?? ''));
            $description = trim((string)($_POST['description'] ?? ''));
            $startDate = normalizeDateInput((string)($_POST['start_date'] ?? ''));
            $endDate = normalizeDateInput((string)($_POST['end_date'] ?? ''));

            if ($name === '' || $description === '') {
                $errors[] = 'Project name and description are required.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO projects (name, description, start_date, end_date) VALUES (:name, :description, :start_date, :end_date)');
                $stmt->execute([
                    'name' => $name,
                    'description' => $description,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                ]);
                $message = 'Project created.';
            }
        }

        if ($action === 'project_update') {
            $projectId = (int)($_POST['project_id'] ?? 0);
            $name = trim((string)($_POST['name'] ?? ''));
            $description = trim((string)($_POST['description'] ?? ''));
            $startDate = normalizeDateInput((string)($_POST['start_date'] ?? ''));
            $endDate = normalizeDateInput((string)($_POST['end_date'] ?? ''));

            if ($projectId <= 0 || $name === '' || $description === '') {
                $errors[] = 'Invalid project update payload.';
            } else {
                $stmt = $pdo->prepare('UPDATE projects SET name = :name, description = :description, start_date = :start_date, end_date = :end_date WHERE project_id = :project_id');
                $stmt->execute([
                    'name' => $name,
                    'description' => $description,
                    'start_date' => $startDate,
                    'end_date' => $endDate,
                    'project_id' => $projectId,
                ]);
                $message = 'Project updated.';
            }
        }

        if ($action === 'project_delete') {
            $projectId = (int)($_POST['project_id'] ?? 0);
            if ($projectId <= 0) {
                $errors[] = 'Invalid project id.';
            } else {
                $stmt = $pdo->prepare('DELETE FROM projects WHERE project_id = :project_id');
                $stmt->execute(['project_id' => $projectId]);
                $message = 'Project deleted.';
            }
        }

        if ($action === 'team_add') {
            $name = trim((string)($_POST['name'] ?? ''));
            $role = trim((string)($_POST['role'] ?? ''));
            $projectIdRaw = trim((string)($_POST['project_id'] ?? ''));
            $projectId = $projectIdRaw === '' ? null : (int)$projectIdRaw;

            if ($name === '' || $role === '') {
                $errors[] = 'Team member name and role are required.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO team (name, role, project_id) VALUES (:name, :role, :project_id)');
                $stmt->execute([
                    'name' => $name,
                    'role' => $role,
                    'project_id' => $projectId,
                ]);
                $message = 'Team member added.';
            }
        }

        if ($action === 'team_update') {
            $teamId = (int)($_POST['team_id'] ?? 0);
            $name = trim((string)($_POST['name'] ?? ''));
            $role = trim((string)($_POST['role'] ?? ''));
            $projectIdRaw = trim((string)($_POST['project_id'] ?? ''));
            $projectId = $projectIdRaw === '' ? null : (int)$projectIdRaw;

            if ($teamId <= 0 || $name === '' || $role === '') {
                $errors[] = 'Invalid team update payload.';
            } else {
                $stmt = $pdo->prepare('UPDATE team SET name = :name, role = :role, project_id = :project_id WHERE team_id = :team_id');
                $stmt->execute([
                    'name' => $name,
                    'role' => $role,
                    'project_id' => $projectId,
                    'team_id' => $teamId,
                ]);
                $message = 'Team member updated.';
            }
        }

        if ($action === 'team_delete') {
            $teamId = (int)($_POST['team_id'] ?? 0);
            if ($teamId <= 0) {
                $errors[] = 'Invalid team id.';
            } else {
                $stmt = $pdo->prepare('DELETE FROM team WHERE team_id = :team_id');
                $stmt->execute(['team_id' => $teamId]);
                $message = 'Team member deleted.';
            }
        }

        if ($action === 'drone_add') {
            $name = trim((string)($_POST['name'] ?? ''));
            $type = trim((string)($_POST['type'] ?? ''));
            $status = trim((string)($_POST['status'] ?? ''));
            $specs = trim((string)($_POST['specs'] ?? ''));

            if ($name === '' || $type === '' || $status === '') {
                $errors[] = 'Drone name, type, and status are required.';
            } else {
                $stmt = $pdo->prepare('INSERT INTO drones (name, type, status, specs) VALUES (:name, :type, :status, :specs)');
                $stmt->execute([
                    'name' => $name,
                    'type' => $type,
                    'status' => $status,
                    'specs' => $specs,
                ]);
                $message = 'Drone added.';
            }
        }

        if ($action === 'drone_update') {
            $droneId = (int)($_POST['drone_id'] ?? 0);
            $name = trim((string)($_POST['name'] ?? ''));
            $type = trim((string)($_POST['type'] ?? ''));
            $status = trim((string)($_POST['status'] ?? ''));
            $specs = trim((string)($_POST['specs'] ?? ''));

            if ($droneId <= 0 || $name === '' || $type === '' || $status === '') {
                $errors[] = 'Invalid drone update payload.';
            } else {
                $stmt = $pdo->prepare('UPDATE drones SET name = :name, type = :type, status = :status, specs = :specs WHERE drone_id = :drone_id');
                $stmt->execute([
                    'name' => $name,
                    'type' => $type,
                    'status' => $status,
                    'specs' => $specs,
                    'drone_id' => $droneId,
                ]);
                $message = 'Drone updated.';
            }
        }

        if ($action === 'drone_delete') {
            $droneId = (int)($_POST['drone_id'] ?? 0);
            if ($droneId <= 0) {
                $errors[] = 'Invalid drone id.';
            } else {
                $stmt = $pdo->prepare('DELETE FROM drones WHERE drone_id = :drone_id');
                $stmt->execute(['drone_id' => $droneId]);
                $message = 'Drone deleted.';
            }
        }

        if ($action === 'media_update') {
            $mediaKey = trim((string)($_POST['media_key'] ?? ''));
            $mediaUrl = trim((string)($_POST['media_url'] ?? ''));
            $allowedKeys = array_keys(getDefaultSiteMedia());

            if (!in_array($mediaKey, $allowedKeys, true)) {
                $errors[] = 'Invalid media key.';
            } elseif (!isValidImageUrl($mediaUrl)) {
                $errors[] = 'Enter a valid image URL (jpg, png, webp, gif, or Unsplash link).';
            } else {
                ensureSiteMediaTable($pdo);
                $stmt = $pdo->prepare('INSERT INTO site_media (media_key, media_url) VALUES (:media_key, :media_url) ON DUPLICATE KEY UPDATE media_url = VALUES(media_url)');
                $stmt->execute([
                    'media_key' => $mediaKey,
                    'media_url' => $mediaUrl,
                ]);
                $message = 'Image updated.';
            }
        }

        if ($action === 'evaluation_delete') {
            $evaluationId = (int)($_POST['evaluation_id'] ?? 0);
            if ($evaluationId <= 0) {
                $errors[] = 'Invalid evaluation id.';
            } else {
                $stmt = $pdo->prepare('DELETE FROM evaluations WHERE evaluation_id = :evaluation_id');
                $stmt->execute(['evaluation_id' => $evaluationId]);
                $message = 'Evaluation deleted.';
            }
        }
    } catch (PDOException $e) {
        $errors[] = 'Database operation failed.';
    }
}

$projects = $pdo->query('SELECT project_id, name, description, start_date, end_date FROM projects ORDER BY project_id DESC')->fetchAll();
$teamMembers = $pdo->query('SELECT team_id, name, role, project_id FROM team ORDER BY team_id DESC')->fetchAll();
$drones = $pdo->query('SELECT drone_id, name, type, status, specs FROM drones ORDER BY drone_id DESC')->fetchAll();
$evaluations = $pdo->query(
    'SELECT
        e.evaluation_id,
        u.name AS user_name,
        p.name AS project_name,
        e.usability_score,
        e.performance_score,
        e.design_score,
        e.support_score,
        e.overall_score,
        e.recommendation,
        e.comment,
        e.created_at
    FROM evaluations e
    LEFT JOIN users u ON u.user_id = e.user_id
    LEFT JOIN projects p ON p.project_id = e.project_id
    ORDER BY e.evaluation_id DESC'
)->fetchAll();
$mediaMap = getSiteMediaMap($pdo);

require_once __DIR__ . '/header.php';
?>
<section class="container">
    <h1>Admin Content Manager</h1>
    <p>Edit website data directly: projects, team, drones, evaluations, and website pictures.</p>

    <?php if ($message): ?>
        <div class="admin-alert admin-success"><?php echo htmlspecialchars($message); ?></div>
    <?php endif; ?>

    <?php if ($errors): ?>
        <div class="admin-alert admin-error">
            <?php foreach ($errors as $error): ?>
                <p><?php echo htmlspecialchars($error); ?></p>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</section>

<section class="container admin-section">
    <h2>User Evaluations (Part 3)</h2>
    <p>Review submissions from the 3.3 evaluation module.</p>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>User</th>
                <th>Project</th>
                <th>Usability</th>
                <th>Performance</th>
                <th>Design</th>
                <th>Support</th>
                <th>Overall</th>
                <th>Recommend</th>
                <th>Comment</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$evaluations): ?>
                <tr><td colspan="12">No evaluations found.</td></tr>
            <?php else: ?>
                <?php foreach ($evaluations as $evaluation): ?>
                    <tr>
                        <td><?php echo (int)$evaluation['evaluation_id']; ?></td>
                        <td><?php echo htmlspecialchars((string)($evaluation['user_name'] ?? 'N/A')); ?></td>
                        <td><?php echo htmlspecialchars((string)($evaluation['project_name'] ?? 'N/A')); ?></td>
                        <td><?php echo (int)$evaluation['usability_score']; ?>/5</td>
                        <td><?php echo (int)$evaluation['performance_score']; ?>/5</td>
                        <td><?php echo (int)$evaluation['design_score']; ?>/5</td>
                        <td><?php echo (int)$evaluation['support_score']; ?>/5</td>
                        <td><?php echo (int)$evaluation['overall_score']; ?>/5</td>
                        <td><?php echo htmlspecialchars(ucfirst((string)$evaluation['recommendation'])); ?></td>
                        <td><?php echo htmlspecialchars((string)$evaluation['comment']); ?></td>
                        <td><?php echo htmlspecialchars((string)$evaluation['created_at']); ?></td>
                        <td>
                            <form method="post" onsubmit="return confirm('Delete this evaluation?');">
                                <input type="hidden" name="evaluation_id" value="<?php echo (int)$evaluation['evaluation_id']; ?>">
                                <button class="admin-delete" type="submit" name="action" value="evaluation_delete">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="container admin-section">
    <h2>Website Pictures</h2>
    <p>Change image URLs used in Home, Projects, Gallery, and Team pages.</p>
    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
            <tr>
                <th>Slot</th>
                <th>Preview</th>
                <th>Image URL</th>
                <th>Action</th>
            </tr>
            </thead>
            <tbody>
            <?php foreach (getDefaultSiteMedia() as $key => $defaultUrl): ?>
                <?php $currentUrl = (string)($mediaMap[$key] ?? $defaultUrl); ?>
                <tr>
                    <form method="post">
                        <td>
                            <code><?php echo htmlspecialchars($key); ?></code>
                            <input type="hidden" name="media_key" value="<?php echo htmlspecialchars($key); ?>">
                        </td>
                        <td><img class="admin-thumb" src="<?php echo htmlspecialchars($currentUrl); ?>" alt="<?php echo htmlspecialchars($key); ?>"></td>
                        <td><input type="url" name="media_url" value="<?php echo htmlspecialchars($currentUrl); ?>" required></td>
                        <td><button class="btn-primary" type="submit" name="action" value="media_update">Save</button></td>
                    </form>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="container admin-section">
    <h2>Add Project</h2>
    <form method="post" class="form-grid">
        <input type="hidden" name="action" value="project_add">
        <label for="project_name">Project Name</label>
        <input id="project_name" name="name" required>
        <label for="project_description">Description</label>
        <textarea id="project_description" name="description" rows="3" required></textarea>
        <div class="admin-grid">
            <div>
                <label for="project_start">Start Date</label>
                <input id="project_start" type="date" name="start_date">
            </div>
            <div>
                <label for="project_end">End Date</label>
                <input id="project_end" type="date" name="end_date">
            </div>
        </div>
        <button class="btn-primary" type="submit">Add Project</button>
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Description</th>
                <th>Start</th>
                <th>End</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$projects): ?>
                <tr><td colspan="6">No projects found.</td></tr>
            <?php else: ?>
                <?php foreach ($projects as $project): ?>
                    <tr>
                        <form method="post">
                            <td>
                                <?php echo (int)$project['project_id']; ?>
                                <input type="hidden" name="project_id" value="<?php echo (int)$project['project_id']; ?>">
                            </td>
                            <td><input name="name" value="<?php echo htmlspecialchars((string)$project['name']); ?>" required></td>
                            <td><textarea name="description" rows="2" required><?php echo htmlspecialchars((string)$project['description']); ?></textarea></td>
                            <td><input type="date" name="start_date" value="<?php echo htmlspecialchars((string)$project['start_date']); ?>"></td>
                            <td><input type="date" name="end_date" value="<?php echo htmlspecialchars((string)$project['end_date']); ?>"></td>
                            <td class="admin-actions">
                                <button class="btn-primary" type="submit" name="action" value="project_update">Save</button>
                            </td>
                        </form>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <form method="post" onsubmit="return confirm('Delete this project?');">
                                <input type="hidden" name="project_id" value="<?php echo (int)$project['project_id']; ?>">
                                <button class="admin-delete" type="submit" name="action" value="project_delete">Delete Project</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="container admin-section">
    <h2>Add Team Member</h2>
    <form method="post" class="form-grid">
        <input type="hidden" name="action" value="team_add">
        <label for="team_name">Name</label>
        <input id="team_name" name="name" required>
        <label for="team_role">Role</label>
        <input id="team_role" name="role" required>
        <label for="team_project">Assigned Project (optional)</label>
        <select id="team_project" name="project_id">
            <option value="">No project</option>
            <?php foreach ($projects as $project): ?>
                <option value="<?php echo (int)$project['project_id']; ?>"><?php echo htmlspecialchars((string)$project['name']); ?></option>
            <?php endforeach; ?>
        </select>
        <button class="btn-primary" type="submit">Add Team Member</button>
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Role</th>
                <th>Project</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$teamMembers): ?>
                <tr><td colspan="5">No team members found.</td></tr>
            <?php else: ?>
                <?php foreach ($teamMembers as $member): ?>
                    <tr>
                        <form method="post">
                            <td>
                                <?php echo (int)$member['team_id']; ?>
                                <input type="hidden" name="team_id" value="<?php echo (int)$member['team_id']; ?>">
                            </td>
                            <td><input name="name" value="<?php echo htmlspecialchars((string)$member['name']); ?>" required></td>
                            <td><input name="role" value="<?php echo htmlspecialchars((string)$member['role']); ?>" required></td>
                            <td>
                                <select name="project_id">
                                    <option value="">No project</option>
                                    <?php foreach ($projects as $project): ?>
                                        <option value="<?php echo (int)$project['project_id']; ?>" <?php echo ((string)$member['project_id'] === (string)$project['project_id']) ? 'selected' : ''; ?>>
                                            <?php echo htmlspecialchars((string)$project['name']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </td>
                            <td class="admin-actions">
                                <button class="btn-primary" type="submit" name="action" value="team_update">Save</button>
                                <button class="admin-delete" type="submit" name="action" value="team_delete" onclick="return confirm('Delete this team member?');">Delete</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>

<section class="container admin-section">
    <h2>Add Drone</h2>
    <form method="post" class="form-grid">
        <input type="hidden" name="action" value="drone_add">
        <label for="drone_name">Name</label>
        <input id="drone_name" name="name" required>
        <label for="drone_type">Type</label>
        <input id="drone_type" name="type" required>
        <label for="drone_status">Status</label>
        <select id="drone_status" name="status" required>
            <option value="Active">Active</option>
            <option value="Inactive">Inactive</option>
            <option value="Maintenance">Maintenance</option>
        </select>
        <label for="drone_specs">Specs</label>
        <textarea id="drone_specs" name="specs" rows="2"></textarea>
        <button class="btn-primary" type="submit">Add Drone</button>
    </form>

    <div class="admin-table-wrap">
        <table class="admin-table">
            <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Type</th>
                <th>Status</th>
                <th>Specs</th>
                <th>Actions</th>
            </tr>
            </thead>
            <tbody>
            <?php if (!$drones): ?>
                <tr><td colspan="6">No drones found.</td></tr>
            <?php else: ?>
                <?php foreach ($drones as $drone): ?>
                    <tr>
                        <form method="post">
                            <td>
                                <?php echo (int)$drone['drone_id']; ?>
                                <input type="hidden" name="drone_id" value="<?php echo (int)$drone['drone_id']; ?>">
                            </td>
                            <td><input name="name" value="<?php echo htmlspecialchars((string)$drone['name']); ?>" required></td>
                            <td><input name="type" value="<?php echo htmlspecialchars((string)$drone['type']); ?>" required></td>
                            <td>
                                <select name="status" required>
                                    <option value="Active" <?php echo $drone['status'] === 'Active' ? 'selected' : ''; ?>>Active</option>
                                    <option value="Inactive" <?php echo $drone['status'] === 'Inactive' ? 'selected' : ''; ?>>Inactive</option>
                                    <option value="Maintenance" <?php echo $drone['status'] === 'Maintenance' ? 'selected' : ''; ?>>Maintenance</option>
                                </select>
                            </td>
                            <td><textarea name="specs" rows="2"><?php echo htmlspecialchars((string)$drone['specs']); ?></textarea></td>
                            <td class="admin-actions">
                                <button class="btn-primary" type="submit" name="action" value="drone_update">Save</button>
                                <button class="admin-delete" type="submit" name="action" value="drone_delete" onclick="return confirm('Delete this drone?');">Delete</button>
                            </td>
                        </form>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</section>
<?php require_once __DIR__ . '/footer.php'; ?>

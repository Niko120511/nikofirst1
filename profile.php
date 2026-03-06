<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';
require_once __DIR__ . '/auth.php';
requireLogin();

$profileErrors = [];
$profileSuccess = '';
$passwordErrors = [];
$passwordSuccess = '';

$currentUserId = (int)$_SESSION['user_id'];

$stmt = $pdo->prepare('SELECT user_id, name, email, role, password FROM users WHERE user_id = :id LIMIT 1');
$stmt->execute(['id' => $currentUserId]);
$user = $stmt->fetch();

if (!$user) {
    session_unset();
    session_destroy();
    header('Location: login.php');
    exit;
}

$name = (string)$user['name'];
$email = (string)$user['email'];
$role = (string)$user['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $newName = trim((string)($_POST['name'] ?? ''));
        $newEmail = trim((string)($_POST['email'] ?? ''));

        if ($newName === '') {
            $profileErrors[] = 'Name is required.';
        }
        if (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $profileErrors[] = 'Valid email is required.';
        }

        if (!$profileErrors) {
            $existsStmt = $pdo->prepare(
                'SELECT user_id FROM users WHERE email = :email AND user_id <> :id LIMIT 1'
            );
            $existsStmt->execute([
                'email' => $newEmail,
                'id' => $currentUserId,
            ]);

            if ($existsStmt->fetch()) {
                $profileErrors[] = 'This email is already in use.';
            } else {
                $updateStmt = $pdo->prepare(
                    'UPDATE users SET name = :name, email = :email WHERE user_id = :id'
                );
                $updateStmt->execute([
                    'name' => $newName,
                    'email' => $newEmail,
                    'id' => $currentUserId,
                ]);

                $_SESSION['user_name'] = $newName;
                $_SESSION['user_email'] = $newEmail;

                $name = $newName;
                $email = $newEmail;
                $profileSuccess = 'Profile updated successfully.';
            }
        }
    }

    if (isset($_POST['update_password'])) {
        $currentPassword = (string)($_POST['current_password'] ?? '');
        $newPassword = (string)($_POST['new_password'] ?? '');
        $confirmPassword = (string)($_POST['confirm_password'] ?? '');

        if (!password_verify($currentPassword, (string)$user['password'])) {
            $passwordErrors[] = 'Current password is incorrect.';
        }
        if (strlen($newPassword) < 6) {
            $passwordErrors[] = 'New password must be at least 6 characters.';
        }
        if ($newPassword !== $confirmPassword) {
            $passwordErrors[] = 'New passwords do not match.';
        }

        if (!$passwordErrors) {
            $passwordStmt = $pdo->prepare('UPDATE users SET password = :password WHERE user_id = :id');
            $passwordStmt->execute([
                'password' => password_hash($newPassword, PASSWORD_DEFAULT),
                'id' => $currentUserId,
            ]);
            $passwordSuccess = 'Password updated successfully.';
        }
    }
}

require_once __DIR__ . '/header.php';
?>
<section class="container profile-wrap">
    <h1>User Profile</h1>
    <p>Manage your account information and security settings.</p>

    <div class="profile-role">
        <strong>Role:</strong> <?php echo htmlspecialchars($role); ?>
    </div>
</section>

<section class="container profile-grid">
    <article class="card profile-card">
        <h2>Profile Details</h2>

        <?php if ($profileSuccess): ?>
            <div class="alert alert-success">
                <p><?php echo htmlspecialchars($profileSuccess); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($profileErrors): ?>
            <div class="alert alert-error">
                <?php foreach ($profileErrors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="form-grid">
            <input type="hidden" name="update_profile" value="1">

            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <button class="btn-primary" type="submit">Save Profile</button>
        </form>
    </article>

    <article class="card profile-card">
        <h2>Change Password</h2>

        <?php if ($passwordSuccess): ?>
            <div class="alert alert-success">
                <p><?php echo htmlspecialchars($passwordSuccess); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($passwordErrors): ?>
            <div class="alert alert-error">
                <?php foreach ($passwordErrors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="form-grid">
            <input type="hidden" name="update_password" value="1">

            <label for="current_password">Current Password</label>
            <input type="password" id="current_password" name="current_password" required>

            <label for="new_password">New Password</label>
            <input type="password" id="new_password" name="new_password" required>

            <label for="confirm_password">Confirm New Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button class="btn-primary" type="submit">Update Password</button>
        </form>
    </article>
</section>

<style>
    .profile-wrap { margin-top: 24px; }
    .profile-role { margin: 12px 0 0; }
    .profile-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 16px; margin-bottom: 28px; }
    .profile-card { padding: 18px; }
</style>
<?php require_once __DIR__ . '/footer.php'; ?>


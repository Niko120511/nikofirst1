<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$error = '';
$email = '';
$successMessage = $_SESSION['success'] ?? '';
unset($_SESSION['success']);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');

    $stmt = $pdo->prepare('SELECT user_id, name, email, role, password FROM users WHERE email = :email LIMIT 1');
    $stmt->execute(['email' => $email]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['user_id'] = (int)$user['user_id'];
        $_SESSION['user_name'] = $user['name'];
        $_SESSION['user_email'] = $user['email'];
        $_SESSION['user_role'] = $user['role'];

        header('Location: dashboard.php');
        exit;
    }

    $error = 'Invalid email or password.';
}

require_once __DIR__ . '/header.php';
?>
<section class="container auth-wrap">
    <div class="card auth-card">
        <h2>Login</h2>
        <p>Sign in to access your SkyHub session dashboard.</p>

        <?php if ($successMessage): ?>
            <div class="alert alert-success">
                <p><?php echo htmlspecialchars($successMessage); ?></p>
            </div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-error">
                <p><?php echo htmlspecialchars($error); ?></p>
            </div>
        <?php endif; ?>

        <form method="post" class="form-grid">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <button class="btn-primary" type="submit">Sign In</button>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/footer.php'; ?>

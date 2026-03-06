<?php
declare(strict_types=1);

require_once __DIR__ . '/config.php';

$errors = [];
$name = '';
$email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $password = (string)($_POST['password'] ?? '');
    $confirmPassword = (string)($_POST['confirm_password'] ?? '');

    if ($name === '') {
        $errors[] = 'Name is required.';
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Valid email is required.';
    }
    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }
    if ($password !== $confirmPassword) {
        $errors[] = 'Passwords do not match.';
    }

    if (!$errors) {
        $existsStmt = $pdo->prepare('SELECT user_id FROM users WHERE email = :email LIMIT 1');
        $existsStmt->execute(['email' => $email]);

        if ($existsStmt->fetch()) {
            $errors[] = 'This email is already registered.';
        } else {
            $insertStmt = $pdo->prepare(
                'INSERT INTO users (name, email, role, password) VALUES (:name, :email, :role, :password)'
            );
            $insertStmt->execute([
                'name' => $name,
                'email' => $email,
                'role' => 'client',
                'password' => password_hash($password, PASSWORD_DEFAULT),
            ]);

            $_SESSION['success'] = 'Registration successful. Please login.';
            header('Location: login.php');
            exit;
        }
    }
}

require_once __DIR__ . '/header.php';
?>
<section class="container auth-wrap">
    <div class="card auth-card">
        <h2>Create Account</h2>
        <p>Register to access dashboard features and submit project feedback.</p>

        <?php if ($errors): ?>
            <div class="alert alert-error">
                <?php foreach ($errors as $error): ?>
                    <p><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <form method="post" class="form-grid">
            <label for="name">Full Name</label>
            <input type="text" id="name" name="name" value="<?php echo htmlspecialchars($name); ?>" required>

            <label for="email">Email</label>
            <input type="email" id="email" name="email" value="<?php echo htmlspecialchars($email); ?>" required>

            <label for="password">Password</label>
            <input type="password" id="password" name="password" required>

            <label for="confirm_password">Confirm Password</label>
            <input type="password" id="confirm_password" name="confirm_password" required>

            <button class="btn-primary" type="submit">Register</button>
        </form>
    </div>
</section>
<?php require_once __DIR__ . '/footer.php'; ?>


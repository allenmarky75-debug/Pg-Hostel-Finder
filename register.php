<?php
$page_title = 'Create owner account';

require 'includes/header.php';

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $password = $_POST['password'];

    if ($name === '') {
        $errors[] = 'Enter your name.';
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Enter a valid email address.';
    }

    if (!preg_match('/^[0-9]{10}$/', $phone)) {
        $errors[] = 'Phone number must be 10 digits.';
    }

    if (strlen($password) < 6) {
        $errors[] = 'Password must be at least 6 characters.';
    }

    if (!$errors) {
        $stmt = $pdo->prepare('SELECT id FROM users WHERE email = ?');
        $stmt->execute([$email]);

        if ($stmt->fetch()) {
            $errors[] = 'An account with this email already exists. Try logging in.';
        } else {
            $stmt = $pdo->prepare('INSERT INTO users (name, email, phone, password) VALUES (?, ?, ?, ?)');
            $stmt->execute([
                $name,
                $email,
                $phone,
                password_hash($password, PASSWORD_DEFAULT),
            ]);

            flash('Account created. Log in to add your first listing.');
            header('Location: login.php');
            exit;
        }
    }
}
?>
<div class="form-box">
    <h1>List your PG or hostel</h1>
    <p class="muted">Create an owner account first. Students can search without one.</p>

    <?php foreach ($errors as $error): ?>
        <div class="alert err"><?= e($error) ?></div>
    <?php endforeach; ?>

    <form method="post">
        <?= csrf_field() ?>
        <label>
            Full name
            <input name="name" required value="<?= e($_POST['name'] ?? '') ?>">
        </label>
        <label>
            Email
            <input type="email" name="email" required value="<?= e($_POST['email'] ?? '') ?>">
        </label>
        <label>
            Phone (10 digits)
            <input name="phone" required maxlength="10" value="<?= e($_POST['phone'] ?? '') ?>">
        </label>
        <label>
            Password
            <input type="password" name="password" required minlength="6">
        </label>
        <button class="btn block">Create account</button>
    </form>

    <p class="muted">Already registered? <a href="login.php">Log in</a></p>
</div>
<?php require 'includes/footer.php'; ?>

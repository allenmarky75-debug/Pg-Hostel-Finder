<?php
$page_title = 'Log in';
require 'includes/header.php';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $stmt = $pdo->prepare('SELECT * FROM users WHERE email = ?');
    $stmt->execute([trim($_POST['email'])]);
    $u = $stmt->fetch();
    if ($u && password_verify($_POST['password'], $u['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $u['id']; $_SESSION['user_name'] = $u['name'];
        header('Location: dashboard.php'); exit;
    }
    $error = 'Email or password is incorrect.';
}
?>
<div class="form-box">
  <h1>Log in</h1>
  <?php if ($error): ?><div class="alert err"><?= e($error) ?></div><?php endif; ?>
  <form method="post">
    <?= csrf_field() ?>
    <label>Email<input type="email" name="email" required></label>
    <label>Password<input type="password" name="password" required></label>
    <button class="btn block">Log in</button>
  </form>
  <p class="muted">New owner? <a href="register.php">Create an account</a></p>
</div>
<?php require 'includes/footer.php'; ?>

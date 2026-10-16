<?php require_once __DIR__ . '/../config.php'; $page_title = $page_title ?? 'PG & Hostel Finder'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($page_title) ?> | RoomKey</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:wght@500;700;800&family=Figtree:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/style.css">
</head>
<body>
<header class="site-header">
  <a class="logo" href="index.php"><span class="logo-key">⌂</span> RoomKey</a>
  <nav>
    <a href="index.php">Find a place</a>
    <?php if (is_logged_in()): ?>
      <a href="dashboard.php">My listings</a>
      <a class="btn btn-small" href="add_listing.php">List your property</a>
      <a href="logout.php">Log out (<?= e($_SESSION['user_name']) ?>)</a>
    <?php else: ?>
      <a href="login.php">Log in</a>
      <a class="btn btn-small" href="register.php">List your property</a>
    <?php endif; ?>
  </nav>
</header>
<main class="wrap">
<?php if ($m = flash()): ?><div class="alert ok"><?= e($m) ?></div><?php endif; ?>

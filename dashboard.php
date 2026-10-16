<?php
$page_title = 'My listings';
require 'includes/header.php';
require_login();
$stmt = $pdo->prepare('SELECT * FROM listings WHERE owner_id = ? ORDER BY created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$rows = $stmt->fetchAll();
?>
<div class="row-between"><h1>My listings</h1><a class="btn" href="add_listing.php">Add new listing</a></div>
<?php if (!$rows): ?><div class="empty">You have not listed anything yet. Add your first PG or hostel so students can find it.</div><?php endif; ?>
<?php if ($rows): ?>
<table class="table">
  <tr><th>Title</th><th>Type</th><th>Rent</th><th>Status</th><th>Actions</th></tr>
  <?php foreach ($rows as $r): ?>
  <tr>
    <td><a href="listing.php?id=<?= $r['id'] ?>"><?= e($r['title']) ?></a><br><small class="muted"><?= e($r['area']) ?>, <?= e($r['city']) ?></small></td>
    <td><?= e($r['type']) ?> · <?= e($r['gender']) ?></td>
    <td><?= money($r['rent']) ?></td>
    <td><?= $r['is_available'] ? 'Available' : 'Full' ?></td>
    <td class="actions">
      <a href="edit_listing.php?id=<?= $r['id'] ?>">Edit</a>
      <form method="post" action="delete_listing.php" onsubmit="return confirm('Delete this listing permanently?')">
        <?= csrf_field() ?><input type="hidden" name="id" value="<?= $r['id'] ?>"><button class="link-danger">Delete</button>
      </form>
    </td>
  </tr>
  <?php endforeach; ?>
</table>
<?php endif; ?>
<?php require 'includes/footer.php'; ?>

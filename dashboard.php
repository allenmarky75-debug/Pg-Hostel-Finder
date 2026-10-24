<?php
$page_title = 'My listings';

require 'includes/header.php';
require_login();

$stmt = $pdo->prepare('SELECT * FROM listings WHERE owner_id = ? ORDER BY created_at DESC');
$stmt->execute([$_SESSION['user_id']]);
$listings = $stmt->fetchAll();
?>
<div class="row-between">
    <h1>My listings</h1>
    <a class="btn" href="add_listing.php">Add new listing</a>
</div>

<?php if (!$listings): ?>
    <div class="empty">You have not listed anything yet. Add your first PG or hostel so students can find it.</div>
<?php endif; ?>

<?php if ($listings): ?>
    <table class="table">
        <tr>
            <th>Title</th>
            <th>Type</th>
            <th>Rent</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($listings as $listing): ?>
            <tr>
                <td>
                    <a href="listing.php?id=<?= $listing['id'] ?>"><?= e($listing['title']) ?></a><br>
                    <small class="muted"><?= e($listing['area']) ?>, <?= e($listing['city']) ?></small>
                </td>
                <td><?= e($listing['type']) ?> · <?= e($listing['gender']) ?></td>
                <td><?= money($listing['rent']) ?></td>
                <td><?= $listing['is_available'] ? 'Available' : 'Full' ?></td>
                <td class="actions">
                    <a href="edit_listing.php?id=<?= $listing['id'] ?>">Edit</a>
                    <form method="post" action="delete_listing.php" onsubmit="return confirm('Delete this listing permanently?')">
                        <?= csrf_field() ?>
                        <input type="hidden" name="id" value="<?= $listing['id'] ?>">
                        <button class="link-danger">Delete</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php endif; ?>

<?php require 'includes/footer.php'; ?>

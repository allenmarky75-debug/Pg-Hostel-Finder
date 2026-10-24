<?php
require 'includes/header.php';

$stmt = $pdo->prepare(
    'SELECT l.*, u.name AS owner_name
    FROM listings l
    JOIN users u ON u.id = l.owner_id
    WHERE l.id = ?'
);
$stmt->execute([(int) ($_GET['id'] ?? 0)]);
$l = $stmt->fetch();

if (!$l) {
    echo '<div class="empty">This listing does not exist or was removed.</div>';
    require 'includes/footer.php';
    exit;
}

$amenities = array_filter(array_map('trim', explode(',', $l['amenities'])));
?>
<a class="back" href="index.php">← Back to results</a>

<div class="detail">
    <div>
        <div class="hero-img" <?= $l['image'] ? 'style="background-image:url(uploads/' . e($l['image']) . ')"' : '' ?>>
            <?php if (!$l['image']): ?>
                <span>No photo added</span>
            <?php endif; ?>
        </div>

        <h1><?= e($l['title']) ?></h1>
        <p class="muted"><?= e($l['address']) ?>, <?= e($l['area']) ?>, <?= e($l['city']) ?></p>

        <h2>About this place</h2>
        <p><?= nl2br(e($l['description'])) ?: 'The owner has not added a description.' ?></p>

        <?php if ($amenities): ?>
            <h2>Amenities</h2>
            <ul class="chips">
                <?php foreach ($amenities as $amenity): ?>
                    <li><?= e($amenity) ?></li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <?php if (!empty($l['map_link'])): ?>
            <h2>Location</h2>
            <p class="muted"><?= e($l['address']) ?>, <?= e($l['area']) ?>, <?= e($l['city']) ?></p>
            <p>
                <a class="btn" target="_blank" rel="noopener noreferrer" href="<?= e($l['map_link']) ?>">
                    View on Google Maps
                </a>
            </p>
        <?php endif; ?>
    </div>

    <aside class="panel">
        <p class="price big"><?= money($l['rent']) ?><small>/month</small></p>

        <dl>
            <dt>Type</dt>
            <dd><?= e($l['type']) ?> for <?= e($l['gender']) ?></dd>

            <dt>Sharing</dt>
            <dd><?= (int) $l['sharing'] ?> per room</dd>

            <dt>Deposit</dt>
            <dd><?= money($l['deposit']) ?></dd>

            <dt>Status</dt>
            <dd><?= $l['is_available'] ? 'Available' : 'Not available now' ?></dd>

            <dt>Owner</dt>
            <dd><?= e($l['owner_name']) ?></dd>
        </dl>

        <a class="btn block" href="tel:<?= e($l['contact_phone']) ?>">
            Call <?= e($l['contact_phone']) ?>
        </a>
        <a class="btn btn-ghost block" href="https://wa.me/91<?= e(preg_replace('/\D/', '', $l['contact_phone'])) ?>?text=<?= urlencode('Hi, I saw "' . $l['title'] . '" on RoomKey. Is it still available?') ?>">
            Message on WhatsApp
        </a>
    </aside>
</div>

<?php require 'includes/footer.php'; ?>

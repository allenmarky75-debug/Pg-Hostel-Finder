<?php
$page_title = 'Find a PG or Hostel';

require 'includes/header.php';

$where = ['is_available = 1'];
$args = [];
$city = trim($_GET['city'] ?? '');
$type = $_GET['type'] ?? '';
$gender = $_GET['gender'] ?? '';
$max = (int) ($_GET['max_rent'] ?? 0);

if ($city !== '') {
    $where[] = '(city LIKE ? OR area LIKE ?)';
    $args[] = "%$city%";
    $args[] = "%$city%";
}

if (in_array($type, ['PG', 'Hostel'])) {
    $where[] = 'type = ?';
    $args[] = $type;
}

if (in_array($gender, ['Boys', 'Girls'])) {
    $where[] = '(gender = ? OR gender = "Any")';
    $args[] = $gender;
}

if ($max > 0) {
    $where[] = 'rent <= ?';
    $args[] = $max;
}

$sort = ($_GET['sort'] ?? '') === 'rent' ? 'rent ASC' : 'created_at DESC';
$stmt = $pdo->prepare(
    'SELECT * FROM listings WHERE ' . implode(' AND ', $where) . " ORDER BY $sort"
);
$stmt->execute($args);
$listings = $stmt->fetchAll();
?>
<section class="hero">
    <h1>Find a room near your college.</h1>
    <p>Browse PGs and hostels listed directly by owners. No broker, no sign-up needed to search.</p>

    <form class="search" method="get">
        <input name="city" placeholder="City or area, e.g. Nashik, College Road" value="<?= e($city) ?>">

        <select name="type">
            <option value="">PG or Hostel</option>
            <option <?= $type === 'PG' ? 'selected' : '' ?>>PG</option>
            <option <?= $type === 'Hostel' ? 'selected' : '' ?>>Hostel</option>
        </select>

        <select name="gender">
            <option value="">Boys or Girls</option>
            <option <?= $gender === 'Boys' ? 'selected' : '' ?>>Boys</option>
            <option <?= $gender === 'Girls' ? 'selected' : '' ?>>Girls</option>
        </select>

        <input type="number" name="max_rent" min="0" step="500" placeholder="Max rent / month" value="<?= $max ?: '' ?>">

        <select name="sort">
            <option value="">Newest first</option>
            <option value="rent" <?= $sort === 'rent ASC' ? 'selected' : '' ?>>Lowest rent</option>
        </select>

        <button class="btn">Search</button>
    </form>
</section>

<p class="count">
    <?= count($listings) ?> place<?= count($listings) === 1 ? '' : 's' ?> found
</p>

<?php if (!$listings): ?>
    <div class="empty">No places match these filters. Try a wider area or a higher rent limit.</div>
<?php endif; ?>

<div class="grid">
    <?php foreach ($listings as $listing): ?>
        <a class="card" href="listing.php?id=<?= $listing['id'] ?>">
            <div class="thumb" <?= $listing['image'] ? 'style="background-image:url(uploads/' . e($listing['image']) . ')"' : '' ?>>
                <?php if (!$listing['image']): ?>
                    <span>No photo</span>
                <?php endif; ?>
                <em class="tag"><?= e($listing['type']) ?> · <?= e($listing['gender']) ?></em>
            </div>

            <div class="card-body">
                <h3><?= e($listing['title']) ?></h3>
                <p class="muted"><?= e($listing['area']) ?>, <?= e($listing['city']) ?></p>
                <p class="price">
                    <?= money($listing['rent']) ?><small>/month</small>
                    <span class="muted"><?= (int) $listing['sharing'] ?>-sharing</span>
                </p>
            </div>
        </a>
    <?php endforeach; ?>
</div>

<?php require 'includes/footer.php'; ?>

<?php
$page_title = 'Edit listing';

require 'includes/header.php';
require_login();
require 'includes/listing_save.php';

$stmt = $pdo->prepare('SELECT * FROM listings WHERE id = ? AND owner_id = ?');
$stmt->execute([
    (int) ($_GET['id'] ?? $_POST['id'] ?? 0),
    $_SESSION['user_id'],
]);
$l = $stmt->fetch();

if (!$l) {
    echo '<div class="empty">Listing not found, or it belongs to another owner.</div>';
    require 'includes/footer.php';
    exit;
}

$errors = [];
$is_edit = true;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    [$data, $errors] = collect_listing($l['image']);

    if (!$errors) {
        $stmt = $pdo->prepare(
            'UPDATE listings
            SET title=?, type=?, gender=?, city=?, area=?, address=?, map_link=?, rent=?,
                deposit=?, sharing=?, amenities=?, description=?, contact_phone=?, image=?, is_available=?
            WHERE id=? AND owner_id=?'
        );

        $stmt->execute([
            $data['title'],
            $data['type'],
            $data['gender'],
            $data['city'],
            $data['area'],
            $data['address'],
            $data['map_link'],
            $data['rent'],
            $data['deposit'],
            $data['sharing'],
            $data['amenities'],
            $data['description'],
            $data['contact_phone'],
            $data['image'],
            isset($_POST['is_available']) ? 1 : 0,
            $l['id'],
            $_SESSION['user_id'],
        ]);

        flash('Listing updated.');
        header('Location: dashboard.php');
        exit;
    }

    $l = array_merge($l, $data, [
        'is_available' => isset($_POST['is_available']) ? 1 : 0,
    ]);
}
?>
<div class="form-box wide">
    <h1>Edit listing</h1>
    <?php include 'includes/listing_form.php'; ?>
</div>
<?php require 'includes/footer.php'; ?>

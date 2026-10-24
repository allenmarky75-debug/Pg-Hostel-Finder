<?php
$page_title = 'Add listing';

require 'includes/header.php';
require_login();
require 'includes/listing_save.php';

$errors = [];
$is_edit = false;
$l = [
    'title' => '',
    'type' => 'PG',
    'gender' => 'Boys',
    'city' => '',
    'area' => '',
    'address' => '',
    'map_link' => '',
    'rent' => '',
    'deposit' => 0,
    'sharing' => 2,
    'amenities' => '',
    'description' => '',
    'contact_phone' => '',
    'is_available' => 1,
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    [$data, $errors] = collect_listing();
    $l = array_merge($l, $data);

    if (!$errors) {
        $stmt = $pdo->prepare(
            'INSERT INTO listings (
                owner_id, title, type, gender, city, area, address, map_link, rent,
                deposit, sharing, amenities, description, contact_phone, image
            ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)'
        );

        $stmt->execute([
            $_SESSION['user_id'],
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
        ]);

        flash('Your listing is live.');
        header('Location: dashboard.php');
        exit;
    }
}
?>
<div class="form-box wide">
    <h1>Add your PG or hostel</h1>
    <?php include 'includes/listing_form.php'; ?>
</div>
<?php require 'includes/footer.php'; ?>

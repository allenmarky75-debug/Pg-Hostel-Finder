<?php
$page_title = 'Add listing';
require 'includes/header.php';
require_login();
require 'includes/listing_save.php';
$errors = []; $is_edit = false;
$l = ['title'=>'','type'=>'PG','gender'=>'Boys','city'=>'','area'=>'','address'=>'','map_link'=>'','rent'=>'','deposit'=>0,'sharing'=>2,
      'amenities'=>'','description'=>'','contact_phone'=>'','is_available'=>1];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    [$d, $errors] = collect_listing();
    $l = array_merge($l, $d);
    if (!$errors) {
        $pdo->prepare('INSERT INTO listings (owner_id,title,type,gender,city,area,address,map_link,rent,deposit,sharing,amenities,description,contact_phone,image)
                       VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)')
            ->execute([$_SESSION['user_id'],$d['title'],$d['type'],$d['gender'],$d['city'],$d['area'],$d['address'],$d['map_link'],$d['rent'],
                       $d['deposit'],$d['sharing'],$d['amenities'],$d['description'],$d['contact_phone'],$d['image']]);
        flash('Your listing is live.');
        header('Location: dashboard.php'); exit;
    }
}
?>
<div class="form-box wide"><h1>Add your PG or hostel</h1>
<?php include 'includes/listing_form.php'; ?></div>
<?php require 'includes/footer.php'; ?>

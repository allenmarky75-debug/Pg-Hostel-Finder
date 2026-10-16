<?php
// Validates POST data; returns [data, errors]. Handles image upload.
function collect_listing($existing_image = null) {
    $d = [
        'title' => trim($_POST['title'] ?? ''), 'type' => $_POST['type'] ?? '', 'gender' => $_POST['gender'] ?? '',
        'city' => trim($_POST['city'] ?? ''), 'area' => trim($_POST['area'] ?? ''), 'address' => trim($_POST['address'] ?? ''),
        'rent' => (int)($_POST['rent'] ?? 0), 'deposit' => (int)($_POST['deposit'] ?? 0), 'sharing' => (int)($_POST['sharing'] ?? 1),
        'amenities' => trim($_POST['amenities'] ?? ''), 'description' => trim($_POST['description'] ?? ''),
        'contact_phone' => trim($_POST['contact_phone'] ?? ''), 'image' => $existing_image,
        'map_link' => trim($_POST['map_link'] ?? ''),
    ];
    $err = [];
    if ($d['title'] === '' || $d['city'] === '' || $d['area'] === '' || $d['address'] === '') $err[] = 'Fill in title, city, area and address.';
    if (!in_array($d['type'], ['PG','Hostel'])) $err[] = 'Choose PG or Hostel.';
    if (!in_array($d['gender'], ['Boys','Girls','Any'])) $err[] = 'Choose who the place is for.';
    if ($d['rent'] <= 0) $err[] = 'Enter a monthly rent above 0.';
    if (!preg_match('/^[0-9]{10}$/', $d['contact_phone'])) $err[] = 'Contact phone must be 10 digits.';

    if ($d['map_link'] !== '' && (strlen($d['map_link']) > 500 ||
        !preg_match('~^https://(www\.)?(google\.[a-z.]+/maps|maps\.google\.[a-z.]+|maps\.app\.goo\.gl|goo\.gl/maps)~i', $d['map_link'])))
        $err[] = 'Enter a valid Google Maps link, or leave that field empty.';

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $f = $_FILES['image'];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($f['tmp_name']);
        $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png'][$mime] ?? null;
        if (!$ext) $err[] = 'Photo must be a JPG or PNG.';
        elseif ($f['size'] > 2 * 1024 * 1024) $err[] = 'Photo must be under 2 MB.';
        elseif (!$err) {
            $name = bin2hex(random_bytes(8)) . '.' . $ext;
            move_uploaded_file($f['tmp_name'], __DIR__ . '/../uploads/' . $name);
            $d['image'] = $name;
        }
    }
    return [$d, $err];
}

<?php
// Read and validate the submitted listing fields, then return the data and any errors.
function collect_listing($existing_image = null)
{
    $data = [
        'title' => trim($_POST['title'] ?? ''),
        'type' => $_POST['type'] ?? '',
        'gender' => $_POST['gender'] ?? '',
        'city' => trim($_POST['city'] ?? ''),
        'area' => trim($_POST['area'] ?? ''),
        'address' => trim($_POST['address'] ?? ''),
        'rent' => (int) ($_POST['rent'] ?? 0),
        'deposit' => (int) ($_POST['deposit'] ?? 0),
        'sharing' => (int) ($_POST['sharing'] ?? 1),
        'amenities' => trim($_POST['amenities'] ?? ''),
        'description' => trim($_POST['description'] ?? ''),
        'contact_phone' => trim($_POST['contact_phone'] ?? ''),
        'image' => $existing_image,
        'map_link' => trim($_POST['map_link'] ?? ''),
    ];

    $errors = [];

    if ($data['title'] === '' || $data['city'] === '' || $data['area'] === '' || $data['address'] === '') {
        $errors[] = 'Fill in title, city, area and address.';
    }

    if (!in_array($data['type'], ['PG', 'Hostel'])) {
        $errors[] = 'Choose PG or Hostel.';
    }

    if (!in_array($data['gender'], ['Boys', 'Girls', 'Any'])) {
        $errors[] = 'Choose who the place is for.';
    }

    if ($data['rent'] <= 0) {
        $errors[] = 'Enter a monthly rent above 0.';
    }

    if (!preg_match('/^[0-9]{10}$/', $data['contact_phone'])) {
        $errors[] = 'Contact phone must be 10 digits.';
    }

    if (
        $data['map_link'] !== ''
        && (
            strlen($data['map_link']) > 500
            || !preg_match(
                '~^https://(www\.)?(google\.[a-z.]+/maps|maps\.google\.[a-z.]+|maps\.app\.goo\.gl|goo\.gl/maps)~i',
                $data['map_link']
            )
        )
    ) {
        $errors[] = 'Enter a valid Google Maps link, or leave that field empty.';
    }

    if (!empty($_FILES['image']['name']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $file = $_FILES['image'];
        $mime = (new finfo(FILEINFO_MIME_TYPE))->file($file['tmp_name']);
        $extension = ['image/jpeg' => 'jpg', 'image/png' => 'png'][$mime] ?? null;

        if (!$extension) {
            $errors[] = 'Photo must be a JPG or PNG.';
        } elseif ($file['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Photo must be under 2 MB.';
        } elseif (!$errors) {
            $filename = bin2hex(random_bytes(8)) . '.' . $extension;
            move_uploaded_file($file['tmp_name'], __DIR__ . '/../uploads/' . $filename);
            $data['image'] = $filename;
        }
    }

    return [$data, $errors];
}

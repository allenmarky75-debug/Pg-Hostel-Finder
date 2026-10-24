<?php
require 'config.php';
require_login();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();

    $stmt = $pdo->prepare('SELECT image FROM listings WHERE id = ? AND owner_id = ?');
    $stmt->execute([(int) $_POST['id'], $_SESSION['user_id']]);
    $listing = $stmt->fetch();

    if ($listing) {
        $stmt = $pdo->prepare('DELETE FROM listings WHERE id = ? AND owner_id = ?');
        $stmt->execute([(int) $_POST['id'], $_SESSION['user_id']]);

        if ($listing['image'] && is_file("uploads/{$listing['image']}")) {
            unlink("uploads/{$listing['image']}");
        }

        flash('Listing deleted.');
    }
}

header('Location: dashboard.php');

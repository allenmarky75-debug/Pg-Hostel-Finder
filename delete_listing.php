<?php
require 'config.php';
require_login();
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    csrf_check();
    $s = $pdo->prepare('SELECT image FROM listings WHERE id = ? AND owner_id = ?');
    $s->execute([(int)$_POST['id'], $_SESSION['user_id']]);
    if ($row = $s->fetch()) {
        $pdo->prepare('DELETE FROM listings WHERE id = ? AND owner_id = ?')->execute([(int)$_POST['id'], $_SESSION['user_id']]);
        if ($row['image'] && is_file("uploads/{$row['image']}")) unlink("uploads/{$row['image']}");
        flash('Listing deleted.');
    }
}
header('Location: dashboard.php');

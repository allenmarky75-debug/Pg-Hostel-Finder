<?php
// ---- Edit these for your machine (XAMPP default shown) ----
define('DB_HOST', 'localhost');
define('DB_NAME', 'pg_finder');
define('DB_USER', 'root');
define('DB_PASS', '');

session_start();

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=utf8mb4', DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Database connection failed. Check config.php and make sure you imported database.sql.');
}

function e($s) { return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function is_logged_in() { return isset($_SESSION['user_id']); }
function require_login() {
    if (!is_logged_in()) { header('Location: login.php'); exit; }
}
function csrf_token() {
    if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_field() { return '<input type="hidden" name="csrf" value="' . csrf_token() . '">'; }
function csrf_check() {
    if (!hash_equals($_SESSION['csrf'] ?? '', $_POST['csrf'] ?? '')) die('Invalid request. Go back and try again.');
}
function flash($msg = null) {
    if ($msg !== null) { $_SESSION['flash'] = $msg; return; }
    $m = $_SESSION['flash'] ?? null; unset($_SESSION['flash']); return $m;
}
function money($n) { return '₹' . number_format((int)$n); }

<?php
require_once 'auth.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }
verify_csrf();

$id = (int)($_POST['id'] ?? 0);
if ($id) {
    $pdo->prepare("DELETE FROM listings WHERE id = ?")->execute([$id]);
    $_SESSION['flash'] = 'Listing deleted.';
}

header('Location: dashboard.php');
exit;

<?php
if (!isset($page_title))       $page_title       = 'Cleveland Renter';
if (!isset($page_description)) $page_description = 'Quality rental properties in Cleveland, Lakewood, and Cleveland Heights.';
if (!isset($current_page))     $current_page     = '';

function nav_link(string $href, string $label, string $current_page): string {
    $active = $current_page === $label ? ' aria-current="page"' : '';
    return '<li><a href="' . BASE_URL . '/' . $href . '"' . $active . '>' . $label . '</a></li>';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= htmlspecialchars($page_title) ?></title>
  <meta name="description" content="<?= htmlspecialchars($page_description) ?>">
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700;800&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="<?= BASE_URL ?>/css/styles.css?v=20260623">
</head>
<body>

<header class="site-header">
  <div class="container header-inner">
    <a href="<?= BASE_URL ?>/index.php" class="logo" aria-label="Cleveland Renter — home">
      <div class="logo-mark" aria-hidden="true">CR</div>
      <div class="logo-text">
        <span class="logo-name">Cleveland Renter</span>
        <span class="logo-tagline">Professional Property Management</span>
      </div>
    </a>

    <nav aria-label="Primary">
      <ul class="primary-nav">
        <?= nav_link('index.php',       'Home',       $current_page) ?>
        <?= nav_link('apartments.php',  'Apartments', $current_page) ?>
        <?= nav_link('application.php', 'Apply',      $current_page) ?>
        <?= nav_link('faq.php',         'FAQ',        $current_page) ?>
        <?= nav_link('contact.php',     'Contact',    $current_page) ?>
      </ul>
    </nav>

    <a href="tel:2163937779" class="header-phone">
      <span class="phone-icon" aria-hidden="true">📞</span>
      (216) 393-7779
    </a>

    <button class="nav-toggle" id="nav-toggle" aria-expanded="false" aria-controls="mobile-menu" aria-label="Open navigation">
      <span class="hamburger" aria-hidden="true">
        <span></span><span></span><span></span>
      </span>
    </button>
  </div>
</header>

<nav id="mobile-menu" class="mobile-menu" aria-label="Mobile navigation">
  <a href="<?= BASE_URL ?>/index.php"       <?= $current_page === 'Home'       ? 'aria-current="page"' : '' ?>>Home</a>
  <a href="<?= BASE_URL ?>/apartments.php"  <?= $current_page === 'Apartments' ? 'aria-current="page"' : '' ?>>Apartments</a>
  <a href="<?= BASE_URL ?>/application.php" <?= $current_page === 'Apply'      ? 'aria-current="page"' : '' ?>>Apply</a>
  <a href="<?= BASE_URL ?>/faq.php"         <?= $current_page === 'FAQ'        ? 'aria-current="page"' : '' ?>>FAQ</a>
  <a href="<?= BASE_URL ?>/contact.php"     <?= $current_page === 'Contact'    ? 'aria-current="page"' : '' ?>>Contact</a>
  <a href="tel:2163937779" class="mobile-phone">📞 (216) 393-7779</a>
</nav>

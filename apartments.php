<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';

$page_title       = 'Available Apartments — Cleveland Renter';
$page_description = 'Browse available rental apartments in Cleveland, Lakewood, and Cleveland Heights managed by Cleveland Renter.';
$current_page     = 'Apartments';

$listings = $pdo->query("SELECT * FROM listings ORDER BY sort_order ASC, id ASC")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<main>

  <section class="page-hero">
    <div class="container">
      <h1>Available Apartments</h1>
      <p>Carefully maintained units across Cleveland, Lakewood, and Cleveland Heights.</p>
    </div>
  </section>

  <section class="apartments-section">
    <div class="container">

      <div class="filters-bar" role="group" aria-label="Filter by neighborhood">
        <button class="filter-btn active" data-filter="all">All Neighborhoods</button>
        <button class="filter-btn" data-filter="cleveland">Cleveland</button>
        <button class="filter-btn" data-filter="lakewood">Lakewood</button>
        <button class="filter-btn" data-filter="cleveland-heights">Cleveland Heights</button>
      </div>

      <?php if ($listings): ?>
      <div class="all-listings-grid">
        <?php foreach ($listings as $l): ?>
        <?php include __DIR__ . '/includes/listing-card.php'; ?>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <p style="color:var(--muted);padding:3rem 0;">No listings available at this time. Check back soon or <a href="contact.php">contact us</a> directly.</p>
      <?php endif; ?>

    </div>
  </section>

  <section class="cta-banner">
    <div class="container">
      <h2>Don't see the right fit?</h2>
      <p>Reach out and tell us what you're looking for — units become available often.</p>
      <div>
        <a href="<?= BASE_URL ?>/contact.php" class="btn btn-white">Contact Us</a>
        <a href="<?= BASE_URL ?>/application.php" class="btn btn-outline">View Application Process</a>
      </div>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

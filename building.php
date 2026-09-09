<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/buildings.php';

$building = get_building(trim($_GET['b'] ?? ''));

if (!$building) {
    http_response_code(404);
    $page_title       = 'Building Not Found — Cleveland Renter';
    $page_description = 'That building could not be found.';
    $current_page     = 'Apartments';
    require_once __DIR__ . '/includes/header.php';
    ?>
    <main>
      <section class="page-hero">
        <div class="container">
          <h1>Building not found</h1>
          <p>We couldn't find that building. It may have been renamed or removed.</p>
        </div>
      </section>
      <section class="apartments-section">
        <div class="container">
          <p style="color:var(--muted);margin-bottom:1.5rem;">Try one of our buildings from the home page, or browse every available unit.</p>
          <a href="<?= BASE_URL ?>/index.php" class="btn btn-primary">Back to Home</a>
          <a href="<?= BASE_URL ?>/apartments.php" class="btn btn-outline">All Apartments</a>
        </div>
      </section>
    </main>
    <?php
    require_once __DIR__ . '/includes/footer.php';
    exit;
}

$page_title       = $building['name'] . ' — ' . $building['city'] . ' — Cleveland Renter';
$page_description = 'Apartments for rent at ' . $building['address'] . ', managed by Cleveland Renter.';
$current_page     = 'Apartments';

// Available units first, then coming soon. Rented units are hidden.
$stmt = $pdo->prepare("
    SELECT * FROM listings
    WHERE building = ? AND status != 'rented'
    ORDER BY FIELD(status, 'available', 'coming-soon'), sort_order ASC, id ASC
");
$stmt->execute([$building['slug']]);
$units = $stmt->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<main>

  <section class="page-hero">
    <div class="container">
      <a href="<?= BASE_URL ?>/index.php" class="building-back">&larr; All buildings</a>
      <h1><?= htmlspecialchars($building['name']) ?></h1>
      <p><?= htmlspecialchars($building['address']) ?></p>
    </div>
  </section>

  <section class="apartments-section">
    <div class="container">

      <div class="building-intro<?= empty($building['image']) ? ' no-image' : '' ?>">
        <?php if (!empty($building['image'])): ?>
        <div class="building-hero-img">
          <img src="<?= BASE_URL ?>/<?= htmlspecialchars($building['image']) ?>" alt="<?= htmlspecialchars($building['name']) ?>">
        </div>
        <?php endif; ?>
        <div class="building-intro-text">
          <span class="section-label"><?= htmlspecialchars($building['city']) ?></span>
          <h2 class="section-title" style="margin-bottom:.5rem;">About this building</h2>
          <p style="color:var(--muted);"><?= htmlspecialchars($building['blurb']) ?></p>
        </div>
      </div>

      <h2 class="section-title" style="margin-top:3rem;">
        <?= $units ? 'Units at ' . htmlspecialchars($building['name']) : 'Availability' ?>
      </h2>

      <?php if ($units): ?>
      <div class="all-listings-grid">
        <?php foreach ($units as $l): ?>
        <?php include __DIR__ . '/includes/listing-card.php'; ?>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <div class="building-empty">
        <p>No open units at <?= htmlspecialchars($building['name']) ?> right now.</p>
        <p style="color:var(--muted);font-size:.92rem;">Units here turn over regularly. Tell us what you're looking for and we'll reach out when one opens up.</p>
        <div style="margin-top:1.25rem;">
          <a href="<?= BASE_URL ?>/contact.php" class="btn btn-primary">Contact Us</a>
          <a href="<?= BASE_URL ?>/apartments.php" class="btn btn-outline">See All Available Units</a>
        </div>
      </div>
      <?php endif; ?>

    </div>
  </section>

  <section class="cta-banner">
    <div class="container">
      <h2>Questions about <?= htmlspecialchars($building['name']) ?>?</h2>
      <p>We're happy to answer questions, share more photos, or schedule a showing.</p>
      <div>
        <a href="<?= BASE_URL ?>/contact.php" class="btn btn-white">Contact Us</a>
        <a href="<?= BASE_URL ?>/application.php" class="btn btn-outline">View Application Process</a>
      </div>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

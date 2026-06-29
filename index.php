<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';

$page_title       = 'Cleveland Renter — Find Your Dream Home in Cleveland';
$page_description = 'Discover quality rental properties in Cleveland, Lakewood, and Cleveland Heights. Professional property management with a focus on your comfort and satisfaction.';
$current_page     = 'Home';

$featured = $pdo->query("SELECT * FROM listings WHERE status = 'available' ORDER BY sort_order ASC, id ASC LIMIT 6")->fetchAll();

require_once __DIR__ . '/includes/header.php';
?>

<main>

  <!-- ── Hero ──────────────────────────────────────────────────────── -->
  <section class="hero">
    <div class="container hero-content">
      <div class="hero-text">
        <div class="hero-badge">&#127968; Cleveland, Lakewood &amp; Cleveland Heights</div>
        <h1>Find Your Dream Home Today</h1>
        <p class="hero-sub">Discover quality rental properties across Northeast Ohio. Professional property management with a focus on your comfort and satisfaction.</p>
        <div class="hero-actions">
          <a href="<?= BASE_URL ?>/apartments.php" class="btn btn-white">Browse Apartments</a>
          <a href="<?= BASE_URL ?>/contact.php" class="btn btn-outline" style="color:rgba(255,255,255,.9);border-color:rgba(255,255,255,.5);">Get in Touch</a>
        </div>
      </div>
      <div class="hero-stats">
        <div class="stat-card"><div class="num">50+</div><div class="lbl">Units Managed</div></div>
        <div class="stat-card"><div class="num">3</div><div class="lbl">Neighborhoods</div></div>
        <div class="stat-card"><div class="num">15+</div><div class="lbl">Years Experience</div></div>
        <div class="stat-card"><div class="num">24h</div><div class="lbl">Maintenance Response</div></div>
      </div>
    </div>
  </section>

  <!-- ── Featured listings ─────────────────────────────────────────── -->
  <?php if ($featured): ?>
  <section class="featured-listings">
    <div class="container">
      <div class="featured-header">
        <div>
          <span class="section-label">Available now</span>
          <h2 class="section-title" style="margin-bottom:0">Featured Apartments</h2>
        </div>
        <a href="<?= BASE_URL ?>/apartments.php" class="btn btn-outline">View all listings</a>
      </div>

      <div class="compact-grid">
        <?php foreach ($featured as $l):
          $zillow = !empty($l['zillow_url']) ? $l['zillow_url'] : '#';
          $is_soon = $l['status'] === 'coming-soon';
        ?>
        <a class="compact-card" href="<?= htmlspecialchars($zillow) ?>" <?= $zillow !== '#' ? 'target="_blank" rel="noopener"' : '' ?>>
          <div class="compact-img-wrap">
            <?php if (!empty($l['image_path'])): ?>
              <img class="compact-img" src="<?= BASE_URL ?>/<?= htmlspecialchars($l['image_path']) ?>" alt="<?= htmlspecialchars($l['name']) ?>">
            <?php else: ?>
              <span class="compact-placeholder">🏠</span>
            <?php endif; ?>
          </div>
          <div class="compact-info">
            <div class="compact-top">
              <div class="compact-name"><?= htmlspecialchars($l['name']) ?></div>
              <span class="compact-status <?= $is_soon ? 'soon' : '' ?>">
                <?= $is_soon ? 'Coming Soon' : 'Available' ?>
              </span>
            </div>
            <div class="compact-hood"><?= htmlspecialchars($l['neighborhood_label']) ?></div>
            <div class="compact-meta">
              <?= htmlspecialchars($l['beds']) ?> bed &middot;
              <?= htmlspecialchars($l['baths']) ?> bath
              <?= $l['sqft'] ? '&middot; ' . number_format($l['sqft']) . ' sq ft' : '' ?>
            </div>
            <div class="compact-price">$<?= number_format($l['rent']) ?> <span>/ mo</span></div>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>
  <?php endif; ?>

  <!-- ── Areas served ───────────────────────────────────────────────── -->
  <section class="areas-section">
    <div class="container">
      <span class="section-label">Where we operate</span>
      <h2 class="section-title">Three great neighborhoods</h2>
      <p class="section-intro">We focus on a tight area so we can give every tenant and every property the attention it deserves.</p>
      <div class="areas-grid">
        <div class="area-card"><div class="area-icon" aria-hidden="true">🌊</div><h3>Lakewood</h3><p>A walkable lakeside city with a lively restaurant row, excellent schools, and easy access to downtown Cleveland.</p></div>
        <div class="area-card"><div class="area-icon" aria-hidden="true">🌳</div><h3>Cleveland Heights</h3><p>A vibrant, diverse suburb with arts, coffee shops, and the Cedar-Lee neighborhood.</p></div>
        <div class="area-card"><div class="area-icon" aria-hidden="true">🏙</div><h3>Cleveland</h3><p>Ohio City, Tremont, and surrounding neighborhoods — urban living with character and walkable amenities.</p></div>
      </div>
    </div>
  </section>

  <!-- ── CTA ───────────────────────────────────────────────────────── -->
  <section class="cta-banner">
    <div class="container">
      <h2>Ready to find your next home?</h2>
      <p>Browse available units, or reach out directly — we're happy to answer questions and schedule a showing.</p>
      <div>
        <a href="<?= BASE_URL ?>/apartments.php" class="btn btn-white">View Apartments</a>
        <a href="<?= BASE_URL ?>/contact.php" class="btn btn-outline">Contact Us</a>
      </div>
    </div>
  </section>

</main>

<?php require_once __DIR__ . '/includes/footer.php'; ?>

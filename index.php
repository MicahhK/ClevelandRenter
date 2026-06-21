<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';

$page_title       = 'Cleveland Renter — Find Your Dream Home in Cleveland';
$page_description = 'Discover quality rental properties in Cleveland, Lakewood, and Cleveland Heights. Professional property management with a focus on your comfort and satisfaction.';
$current_page     = 'Home';

$featured = $pdo->query("SELECT * FROM listings WHERE status != 'rented' ORDER BY sort_order ASC, id ASC LIMIT 3")->fetchAll();

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

  <!-- ── Why us ────────────────────────────────────────────────────── -->
  <section class="why-us">
    <div class="container">
      <span class="section-label">Why choose us</span>
      <h2 class="section-title">A local landlord who picks up the phone</h2>
      <p class="section-intro">We're not a faceless management company. We own and care for every property ourselves — and we're here when you need us.</p>
      <div class="features-grid">
        <div class="feature-card"><div class="feature-icon" aria-hidden="true">🏠</div><h3>Well-Maintained Homes</h3><p>Every unit is cleaned, inspected, and move-in ready.</p></div>
        <div class="feature-card"><div class="feature-icon" aria-hidden="true">⚡</div><h3>Fast Maintenance</h3><p>We respond to requests within 24 hours. Emergencies get same-day attention.</p></div>
        <div class="feature-card"><div class="feature-icon" aria-hidden="true">🤝</div><h3>Fair &amp; Transparent</h3><p>No hidden fees. Lease terms written in plain English. Deposits by the book.</p></div>
        <div class="feature-card"><div class="feature-icon" aria-hidden="true">📍</div><h3>Great Locations</h3><p>Close to dining, transit, parks, and downtown Cleveland.</p></div>
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
      <div class="listings-grid">
        <?php foreach ($featured as $l): ?>
        <?php include __DIR__ . '/includes/listing-card.php'; ?>
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

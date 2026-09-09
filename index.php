<?php
header('Cache-Control: no-store, no-cache, must-revalidate');
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/buildings.php';

$page_title       = 'Cleveland Renter — Find Your Dream Home in Cleveland';
$page_description = 'Discover quality rental properties in Cleveland, Lakewood, and Cleveland Heights. Professional property management with a focus on your comfort and satisfaction.';
$current_page     = 'Home';

// Open-unit counts per building. Guarded so the home page still renders if
// migrate_buildings.php hasn't been run yet and the `building` column is absent.
$unit_counts = [];
try {
    $rows = $pdo->query("
        SELECT building, status, COUNT(*) AS n
        FROM listings
        WHERE building IS NOT NULL AND building != '' AND status != 'rented'
        GROUP BY building, status
    ")->fetchAll();
    foreach ($rows as $r) {
        $unit_counts[$r['building']][$r['status']] = (int)$r['n'];
    }
} catch (PDOException $e) {
    error_log('Building counts unavailable: ' . $e->getMessage());
}

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

  <!-- ── Our buildings ─────────────────────────────────────────────── -->
  <section class="featured-listings">
    <div class="container">
      <div class="featured-header">
        <div>
          <span class="section-label">Our properties</span>
          <h2 class="section-title" style="margin-bottom:0">Our Buildings</h2>
        </div>
        <a href="<?= BASE_URL ?>/apartments.php" class="btn btn-outline">View all listings</a>
      </div>

      <div class="buildings-grid">
        <?php foreach (all_buildings() as $b):
          $avail = $unit_counts[$b['slug']]['available']   ?? 0;
          $soon  = $unit_counts[$b['slug']]['coming-soon'] ?? 0;
          if ($avail) {
              $status_text  = $avail . ' unit' . ($avail === 1 ? '' : 's') . ' available';
              $status_class = '';
          } elseif ($soon) {
              $status_text  = $soon . ' coming soon';
              $status_class = ' soon';
          } else {
              $status_text  = 'Contact for availability';
              $status_class = ' none';
          }
        ?>
        <a class="building-card" href="<?= BASE_URL ?>/building.php?b=<?= urlencode($b['slug']) ?>">
          <div class="building-img-wrap">
            <?php if (!empty($b['image'])): ?>
              <img class="building-img" src="<?= BASE_URL ?>/<?= htmlspecialchars($b['image']) ?>" alt="<?= htmlspecialchars($b['name']) ?>">
            <?php else: ?>
              <span class="building-placeholder" aria-hidden="true">&#127968;</span>
            <?php endif; ?>
          </div>
          <div class="building-info">
            <div class="building-name"><?= htmlspecialchars($b['name']) ?></div>
            <div class="building-city"><?= htmlspecialchars($b['city']) ?></div>
            <span class="building-status<?= $status_class ?>"><?= htmlspecialchars($status_text) ?></span>
          </div>
        </a>
        <?php endforeach; ?>
      </div>
    </div>
  </section>

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

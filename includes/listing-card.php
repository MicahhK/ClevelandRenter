<?php
// Expects $l — a listing row from the database.
$amenities = [];
if (!empty($l['amenities'])) {
    $amenities = json_decode($l['amenities'], true) ?: [];
}
$badge_class  = $l['status'] === 'available' ? 'available' : '';
$badge_text   = match($l['status']) {
    'available'   => 'Available Now',
    'coming-soon' => 'Coming Soon',
    default       => 'Rented',
};
$badge_style  = $l['status'] === 'coming-soon' ? 'style="background:#e6820a"' : '';
$btn_class    = $l['status'] === 'available' ? 'btn-primary' : 'btn-outline';
$btn_text     = $l['status'] === 'available' ? 'Inquire / Apply' : 'Express Interest';
?>
<article class="listing-card" data-neighborhood="<?= htmlspecialchars($l['neighborhood']) ?>">
  <div class="listing-img">
    <?php if (!empty($l['image_path'])): ?>
      <img src="<?= BASE_URL ?>/<?= htmlspecialchars($l['image_path']) ?>" alt="<?= htmlspecialchars($l['name']) ?>">
    <?php else: ?>
      🏠
    <?php endif; ?>
    <span class="listing-badge <?= $badge_class ?>" <?= $badge_style ?>><?= $badge_text ?></span>
  </div>
  <div class="listing-body">
    <div class="listing-neighborhood"><?= htmlspecialchars($l['neighborhood_label']) ?></div>
    <div class="listing-name"><?= htmlspecialchars($l['name']) ?></div>
    <div class="listing-meta">
      <span>🛏 <?= htmlspecialchars($l['beds']) ?> bed</span>
      <span>🚿 <?= htmlspecialchars($l['baths']) ?> bath</span>
      <?php if ($l['sqft']): ?><span>📐 <?= number_format($l['sqft']) ?> sq ft</span><?php endif; ?>
    </div>
    <p class="listing-blurb"><?= htmlspecialchars($l['blurb']) ?></p>
    <?php if ($amenities): ?>
    <ul style="font-size:.85rem;color:var(--muted);list-style:none;display:flex;flex-wrap:wrap;gap:.4rem .9rem;margin-bottom:1rem;">
      <?php foreach ($amenities as $a): ?>
        <li>✓ <?= htmlspecialchars($a) ?></li>
      <?php endforeach; ?>
    </ul>
    <?php endif; ?>
    <div class="listing-footer">
      <div class="listing-rent">$<?= number_format($l['rent']) ?> <span>/ mo</span></div>
      <?php if ($l['status'] !== 'rented'): ?>
      <a href="<?= BASE_URL ?>/contact.php?unit=<?= urlencode($l['slug']) ?>"
         class="btn <?= $btn_class ?>" style="padding:.5rem 1rem;font-size:.85rem;">
        <?= $btn_text ?>
      </a>
      <?php endif; ?>
    </div>
  </div>
</article>

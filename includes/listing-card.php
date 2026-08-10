<?php
// Expects $l — a listing row from the database.
$amenities = [];
if (!empty($l['amenities'])) {
    $amenities = json_decode($l['amenities'], true) ?: [];
}
$status_text  = match($l['status']) {
    'available'   => 'Available Now',
    'coming-soon' => 'Coming Soon',
    default       => 'Rented',
};
$status_color = match($l['status']) {
    'available'   => '#15803d',
    'coming-soon' => '#e6820a',
    default       => '#6b7280',
};
$btn_class = $l['status'] === 'available' ? 'btn-primary' : 'btn-outline';
$btn_text  = $l['status'] === 'available' ? 'Inquire / Apply' : 'Express Interest';
?>
<article class="listing-card" data-neighborhood="<?= htmlspecialchars($l['neighborhood']) ?>" data-beds="<?= htmlspecialchars($l['beds']) ?>">
  <div class="listing-img">
    <?php if (!empty($l['image_path'])): ?>
      <img src="<?= BASE_URL ?>/<?= htmlspecialchars($l['image_path']) ?>" alt="<?= htmlspecialchars($l['name']) ?>">
    <?php else: ?>
      🏠
    <?php endif; ?>
    <span class="listing-badge"><?= htmlspecialchars($l['beds']) ?> bed &middot; <?= htmlspecialchars($l['baths']) ?> bath</span>
  </div>
  <div class="listing-body">
    <div class="listing-neighborhood"><?= htmlspecialchars($l['neighborhood_label']) ?></div>
    <div class="listing-name"><?= htmlspecialchars($l['name']) ?></div>
    <div class="listing-meta">
      <span style="color:<?= $status_color ?>;font-weight:600;"><?= $status_text ?></span>
      <?php if ($l['sqft']): ?><span><?= number_format($l['sqft']) ?> sq ft</span><?php endif; ?>
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
      <div class="listing-rent"><?= $l['rent'] > 0 ? '$' . number_format($l['rent']) . ' <span>/ mo</span>' : 'Contact for pricing' ?></div>
      <?php if ($l['status'] !== 'rented'): ?>
      <?php
        $btn_href   = !empty($l['zillow_url']) ? htmlspecialchars($l['zillow_url']) : BASE_URL . '/contact.php?unit=' . urlencode($l['slug']);
        $btn_target = !empty($l['zillow_url']) ? ' target="_blank" rel="noopener"' : '';
      ?>
      <a href="<?= $btn_href ?>"<?= $btn_target ?>
         class="btn <?= $btn_class ?>" style="padding:.5rem 1rem;font-size:.85rem;">
        <?= $btn_text ?>
      </a>
      <?php endif; ?>
    </div>
  </div>
</article>

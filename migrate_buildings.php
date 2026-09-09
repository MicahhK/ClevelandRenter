<?php
/**
 * One-time migration: adds the `building` column to `listings` and back-fills
 * the units that already exist. Safe to run more than once.
 *
 * Visit /migrate_buildings.php in your browser ONCE after deploying,
 * then DELETE this file from the server.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/buildings.php';

$messages = [];

// Add the column only if it isn't there — MySQL has no ADD COLUMN IF NOT EXISTS.
$exists = $pdo->query("SHOW COLUMNS FROM listings LIKE 'building'")->fetch();
if ($exists) {
    $messages[] = 'Column `building` already exists — skipped.';
} else {
    $pdo->exec("ALTER TABLE listings ADD COLUMN building VARCHAR(50) DEFAULT NULL AFTER neighborhood_label");
    $pdo->exec("ALTER TABLE listings ADD INDEX idx_building (building)");
    $messages[] = 'Column `building` added and indexed.';
}

// Back-fill units that already exist, matched on their slug.
$backfill = [
    '16700-clifton-blvd-unit-3' => '16700-clifton',
    '2162-maplewood-rd'         => '2162-maplewood',
];

$stmt = $pdo->prepare("UPDATE listings SET building = ? WHERE slug = ? AND (building IS NULL OR building = '')");
foreach ($backfill as $slug => $building) {
    $stmt->execute([$building, $slug]);
    $messages[] = $stmt->rowCount()
        ? "Assigned `$slug` to building `$building`."
        : "No unassigned listing with slug `$slug` — skipped.";
}

// Report anything still unassigned so it can be fixed in the admin panel.
$unassigned = $pdo->query("SELECT name, slug FROM listings WHERE building IS NULL OR building = ''")->fetchAll();
$known      = building_slugs();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Building Migration — Cleveland Renter</title>
<style>body{font-family:system-ui,sans-serif;max-width:640px;margin:3rem auto;padding:0 1rem;line-height:1.6;}
li{margin:.4rem 0;} code{background:#f0f2f5;padding:.1rem .35rem;border-radius:4px;}
.warn{background:#fff7ed;border:1px solid #fdba74;padding:1rem;border-radius:8px;margin-top:1.5rem;}</style>
</head>
<body>
<h1>Building migration</h1>
<ul><?php foreach ($messages as $m) echo '<li>' . htmlspecialchars($m) . '</li>'; ?></ul>

<h2>Buildings configured</h2>
<ul><?php foreach ($known as $k) echo '<li><code>' . htmlspecialchars($k) . '</code></li>'; ?></ul>

<h2>Listings with no building</h2>
<?php if ($unassigned): ?>
<p>Assign these in the admin panel (Listings &rarr; Edit &rarr; Building):</p>
<ul><?php foreach ($unassigned as $u)
      echo '<li>' . htmlspecialchars($u['name']) . ' <code>' . htmlspecialchars($u['slug']) . '</code></li>'; ?></ul>
<?php else: ?>
<p>None — every listing is assigned to a building.</p>
<?php endif; ?>

<div class="warn"><strong>Delete <code>migrate_buildings.php</code> from the server now.</strong></div>
</body>
</html>

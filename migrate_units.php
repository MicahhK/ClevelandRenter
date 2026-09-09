<?php
/**
 * One-time migration: loads the full unit inventory from the
 * "apartment details" spreadsheet. Safe to run more than once — it upserts
 * on slug, so re-running just re-applies the same values.
 *
 * Requires migrate_buildings.php to have run first (needs the `building` column).
 *
 * Visit /migrate_units.php in your browser ONCE, then DELETE this file.
 */
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';
require_once __DIR__ . '/includes/buildings.php';

// Guard: the building column has to exist before this can run.
if (!$pdo->query("SHOW COLUMNS FROM listings LIKE 'building'")->fetch()) {
    die('<pre>Run migrate_buildings.php first — the `building` column is missing.</pre>');
}

/**
 * The inventory, transcribed from "apartment details - Sheet1.csv".
 * Every unit is coming-soon except 16700 Clifton Unit 3, which is available.
 * Neighborhood is derived from the building config, so it can't drift.
 */
$units = [
    // slug, building, unit label, beds, baths, sqft, rent, status, available, zillow
    ['9414-clifton-blvd-unit-1',  '9414-clifton',   '1',  '3',       '1',   1400, 1500, 'coming-soon', 'June 2026', 'https://www.zillow.com/homedetails/9414-Clifton-Blvd-APT-1-Cleveland-OH-44102/2063406308_zpid/'],
    ['9414-clifton-blvd-unit-2',  '9414-clifton',   '2',  '1 + Den', '1',    750, 1050, 'coming-soon', 'June 2026', 'https://www.zillow.com/homedetails/9414-Clifton-Blvd-APT-2-Cleveland-OH-44102/2087706273_zpid/'],
    ['9414-clifton-blvd-unit-3',  '9414-clifton',   '3',  '1',       '1',    600,  950, 'coming-soon', 'June 2026', 'https://www.zillow.com/homedetails/9414-Clifton-Blvd-APT-3-Cleveland-OH-44102/2093817350_zpid/'],
    ['9414-clifton-blvd-unit-4',  '9414-clifton',   '4',  '2',       '1',    700, 1150, 'coming-soon', 'July 2027', 'https://www.zillow.com/homedetails/9414-Clifton-Blvd-APT-4-Cleveland-OH-44102/2094903616_zpid/'],

    ['16700-clifton-blvd-unit-1', '16700-clifton',  '1',  '3',       '1',   1629, 1750, 'coming-soon', 'June 2026', 'https://www.zillow.com/homedetails/16700-Clifton-Blvd-APT-1-Lakewood-OH-44107/2084819318_zpid/'],
    ['16700-clifton-blvd-unit-2', '16700-clifton',  '2',  '3',       '1',   1613, 1750, 'coming-soon', 'June 2026', 'https://www.zillow.com/homedetails/16700-Clifton-Blvd-APT-2-Lakewood-OH-44107/2084622808_zpid/'],
    ['16700-clifton-blvd-unit-3', '16700-clifton',  '3',  '1',       '1',    806, 1000, 'available',   'June 2026', 'https://www.zillow.com/homedetails/16700-Clifton-Blvd-3-Lakewood-OH-44107/2082147069_zpid/'],

    ['1553-wagar-ave-unit-1',     'wagar',          '1',  '2',       '1',   1144, 1400, 'coming-soon', 'June 2026', 'https://www.zillow.com/homedetails/1553-Wagar-Ave-1-Lakewood-OH-44107/2058215043_zpid/'],
    ['1553-wagar-ave-unit-2',     'wagar',          '2',  '2',       '1',   1144, 1400, 'coming-soon', 'June 2026', 'https://www.zillow.com/homedetails/1553-Wagar-Ave-2-Lakewood-OH-44107/2096927164_zpid/'],
    ['1553-wagar-ave-unit-3',     'wagar',          '3',  '1',       '1',    572, 1000, 'coming-soon', 'June 2026', 'https://www.zillow.com/homedetails/1553-Wagar-Ave-3-Lakewood-OH-44107/2096908909_zpid/'],

    ['2052-wascana-ave-unit-1',   '2052-wascana',   '1',  '2',       '1',   1032, 1450, 'coming-soon', 'June 2026', 'https://www.zillow.com/homedetails/2052-Wascana-Ave-1-Lakewood-OH-44107/2056595041_zpid/'],
    ['2052-wascana-ave-unit-2',   '2052-wascana',   '2',  '2',       '1',   1008, 1450, 'coming-soon', 'June 2026', 'https://www.zillow.com/homedetails/2052-Wascana-Ave-2-Lakewood-OH-44107/2095779734_zpid/'],

    // Single-unit property — keeps its original slug, so no duplicate row is created.
    ['2162-maplewood-rd',         '2162-maplewood', null, '3',       '1.5', 1470, 2000, 'coming-soon', 'June 2027', 'https://www.zillow.com/homedetails/2162-Maplewood-Rd-Cleveland-Heights-OH-44118/33660138_zpid/'],
];

// Neighborhood slug per city, so the filter values stay consistent with the admin dropdown.
$city_slugs = [
    'Cleveland'         => 'cleveland',
    'Lakewood'          => 'lakewood',
    'Cleveland Heights' => 'cleveland-heights',
];

$sql = "INSERT INTO listings
          (slug, name, neighborhood, neighborhood_label, building, beds, baths, sqft, rent,
           status, blurb, zillow_url, sort_order)
        VALUES
          (:slug, :name, :neighborhood, :neighborhood_label, :building, :beds, :baths, :sqft, :rent,
           :status, :blurb, :zillow_url, :sort_order)
        ON DUPLICATE KEY UPDATE
          name=VALUES(name), neighborhood=VALUES(neighborhood),
          neighborhood_label=VALUES(neighborhood_label), building=VALUES(building),
          beds=VALUES(beds), baths=VALUES(baths), sqft=VALUES(sqft), rent=VALUES(rent),
          status=VALUES(status), blurb=VALUES(blurb), zillow_url=VALUES(zillow_url),
          sort_order=VALUES(sort_order)";
$stmt = $pdo->prepare($sql);

$rows = [];
foreach ($units as [$slug, $bslug, $unit_no, $beds, $baths, $sqft, $rent, $status, $available, $zillow]) {
    $b = get_building($bslug);
    if (!$b) { $rows[] = ['slug' => $slug, 'result' => "SKIPPED — unknown building `$bslug`"]; continue; }

    $name  = $b['name'] . ($unit_no !== null ? ' Unit ' . $unit_no : '');
    $blurb = sprintf(
        '%s bed, %s bath at %s in %s — %s sq ft. %s',
        $beds, $baths, $b['name'], $b['city'], number_format($sqft),
        $status === 'available' ? 'Available now.' : 'Available ' . $available . '.'
    );

    $stmt->execute([
        'slug'               => $slug,
        'name'               => $name,
        'neighborhood'       => $city_slugs[$b['city']] ?? 'cleveland',
        'neighborhood_label' => $b['city'],
        'building'           => $bslug,
        'beds'               => $beds,
        'baths'              => $baths,
        'sqft'               => $sqft,
        'rent'               => $rent,
        'status'             => $status,
        'blurb'              => $blurb,
        'zillow_url'         => $zillow,
        'sort_order'         => $unit_no !== null ? (int)$unit_no : 1,
    ]);

    // rowCount(): 1 = inserted, 2 = updated, 0 = already identical.
    $rows[] = ['slug' => $slug, 'name' => $name, 'result' => match ($stmt->rowCount()) {
        1       => 'inserted',
        2       => 'updated',
        default => 'unchanged',
    }];
}

// Anything in the table that this migration doesn't know about — reported, never deleted.
$known  = array_column($units, 0);
$in     = implode(',', array_fill(0, count($known), '?'));
$orphan = $pdo->prepare("SELECT name, slug, status FROM listings WHERE slug NOT IN ($in)");
$orphan->execute($known);
$orphans = $orphan->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Unit Migration — Cleveland Renter</title>
<style>body{font-family:system-ui,sans-serif;max-width:760px;margin:3rem auto;padding:0 1rem;line-height:1.6;}
table{border-collapse:collapse;width:100%;margin:1rem 0;} th,td{text-align:left;padding:.4rem .6rem;border-bottom:1px solid #e2e6ec;font-size:.9rem;}
th{background:#f7f8fa;} code{background:#f0f2f5;padding:.1rem .35rem;border-radius:4px;font-size:.85em;}
.warn{background:#fff7ed;border:1px solid #fdba74;padding:1rem;border-radius:8px;margin-top:1.5rem;}</style>
</head>
<body>
<h1>Unit migration</h1>
<p><?= count($rows) ?> units processed.</p>
<table>
  <tr><th>Unit</th><th>Slug</th><th>Result</th></tr>
  <?php foreach ($rows as $r): ?>
  <tr><td><?= htmlspecialchars($r['name'] ?? '—') ?></td>
      <td><code><?= htmlspecialchars($r['slug']) ?></code></td>
      <td><?= htmlspecialchars($r['result']) ?></td></tr>
  <?php endforeach; ?>
</table>

<h2>Listings not in the spreadsheet</h2>
<?php if ($orphans): ?>
<p>These are still in the database. Nothing was deleted — remove them in the admin panel if they're stale:</p>
<ul><?php foreach ($orphans as $o)
      echo '<li>' . htmlspecialchars($o['name']) . ' <code>' . htmlspecialchars($o['slug']) . '</code> — ' . htmlspecialchars($o['status']) . '</li>'; ?></ul>
<?php else: ?>
<p>None — the table matches the spreadsheet exactly.</p>
<?php endif; ?>

<div class="warn"><strong>Delete <code>migrate_units.php</code> from the server now.</strong></div>
</body>
</html>

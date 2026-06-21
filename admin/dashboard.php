<?php
require_once 'auth.php';
require_once __DIR__ . '/../includes/db.php';

$flash = $_SESSION['flash'] ?? '';
unset($_SESSION['flash']);

$listings = $pdo->query("SELECT * FROM listings ORDER BY sort_order ASC, id ASC")->fetchAll();

$status_labels = ['available' => 'Available', 'coming-soon' => 'Coming Soon', 'rented' => 'Rented'];
$status_colors = ['available' => '#15803d', 'coming-soon' => '#b45309', 'rented' => '#6b7280'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Dashboard — Cleveland Renter Admin</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: system-ui, sans-serif; background: #f0f2f5; color: #1a1d23; }
    .topbar { background: #1e3a5f; color: #fff; padding: .85rem 2rem; display: flex; justify-content: space-between; align-items: center; }
    .topbar h1 { font-size: 1.05rem; font-weight: 700; }
    .topbar a { color: rgba(255,255,255,.75); text-decoration: none; font-size: .88rem; }
    .topbar a:hover { color: #fff; }
    .main { max-width: 1100px; margin: 2rem auto; padding: 0 1.5rem; }
    .toolbar { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; flex-wrap: wrap; gap: .75rem; }
    .toolbar h2 { font-size: 1.25rem; }
    .btn { display: inline-flex; align-items: center; gap: .35rem; padding: .55rem 1.2rem; border-radius: 7px; font-size: .88rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
    .btn-primary { background: #2f5bd0; color: #fff; }
    .btn-primary:hover { background: #4a73e8; }
    .btn-sm { padding: .35rem .8rem; font-size: .8rem; }
    .btn-edit { background: #eef2fc; color: #2f5bd0; }
    .btn-edit:hover { background: #d4e0f7; }
    .btn-delete { background: #fff0f0; color: #b91c1c; }
    .btn-delete:hover { background: #fee2e2; }
    .flash { background: #f0fdf4; border: 1px solid #86efac; color: #15803d; padding: .75rem 1rem; border-radius: 8px; margin-bottom: 1.5rem; font-size: .9rem; }
    table { width: 100%; border-collapse: collapse; background: #fff; border-radius: 10px; overflow: hidden; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    th { background: #f7f8fa; text-align: left; padding: .75rem 1rem; font-size: .78rem; text-transform: uppercase; letter-spacing: .06em; color: #5a6272; border-bottom: 1px solid #e2e6ec; }
    td { padding: .85rem 1rem; border-bottom: 1px solid #f0f2f5; font-size: .9rem; vertical-align: middle; }
    tr:last-child td { border-bottom: none; }
    tr:hover td { background: #fafbfc; }
    .status-badge { display: inline-block; padding: .2rem .65rem; border-radius: 999px; font-size: .75rem; font-weight: 700; }
    .rent-cell { font-weight: 700; color: #1e3a5f; }
    .actions { display: flex; gap: .5rem; }
    .view-site { font-size: .8rem; color: #5a6272; text-decoration: none; }
    .view-site:hover { color: #2f5bd0; }
  </style>
</head>
<body>
  <div class="topbar">
    <h1>Cleveland Renter — Admin</h1>
    <div style="display:flex;gap:1.5rem;align-items:center;">
      <a href="../index.php" target="_blank">View Site ↗</a>
      <a href="logout.php">Log Out</a>
    </div>
  </div>

  <div class="main">
    <?php if ($flash): ?>
      <div class="flash"><?= htmlspecialchars($flash) ?></div>
    <?php endif; ?>

    <div class="toolbar">
      <h2>Listings (<?= count($listings) ?>)</h2>
      <a href="edit.php" class="btn btn-primary">+ Add Listing</a>
    </div>

    <table>
      <thead>
        <tr>
          <th>Name</th>
          <th>Neighborhood</th>
          <th>Beds / Baths</th>
          <th>Rent</th>
          <th>Status</th>
          <th>Order</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($listings as $l): ?>
        <tr>
          <td>
            <strong><?= htmlspecialchars($l['name']) ?></strong><br>
            <a href="../contact.php?unit=<?= urlencode($l['slug']) ?>" class="view-site" target="_blank">view listing →</a>
          </td>
          <td><?= htmlspecialchars($l['neighborhood_label']) ?></td>
          <td><?= htmlspecialchars($l['beds']) ?> bd / <?= htmlspecialchars($l['baths']) ?> ba<?= $l['sqft'] ? ' · ' . number_format($l['sqft']) . ' sqft' : '' ?></td>
          <td class="rent-cell">$<?= number_format($l['rent']) ?>/mo</td>
          <td>
            <span class="status-badge" style="background:<?= $status_colors[$l['status']] ?>22;color:<?= $status_colors[$l['status']] ?>">
              <?= $status_labels[$l['status']] ?>
            </span>
          </td>
          <td><?= (int)$l['sort_order'] ?></td>
          <td>
            <div class="actions">
              <a href="edit.php?id=<?= $l['id'] ?>" class="btn btn-sm btn-edit">Edit</a>
              <form method="POST" action="delete.php" onsubmit="return confirm('Delete <?= htmlspecialchars(addslashes($l['name'])) ?>?')">
                <input type="hidden" name="id" value="<?= $l['id'] ?>">
                <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
                <button type="submit" class="btn btn-sm btn-delete">Delete</button>
              </form>
            </div>
          </td>
        </tr>
        <?php endforeach; ?>
        <?php if (empty($listings)): ?>
        <tr><td colspan="7" style="text-align:center;color:#5a6272;padding:2rem;">No listings yet. <a href="edit.php">Add one.</a></td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>

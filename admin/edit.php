<?php
require_once 'auth.php';
require_once __DIR__ . '/../includes/db.php';

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$listing = [];
$errors = [];

if ($id) {
    $stmt = $pdo->prepare("SELECT * FROM listings WHERE id = ?");
    $stmt->execute([$id]);
    $listing = $stmt->fetch();
    if (!$listing) { header('Location: dashboard.php'); exit; }
}

// Restore form values after a failed save
if (!empty($_SESSION['form_data'])) {
    $listing = array_merge($listing, $_SESSION['form_data']);
    $errors  = $_SESSION['form_errors'] ?? [];
    unset($_SESSION['form_data'], $_SESSION['form_errors']);
}

$amenities_str = '';
if (!empty($listing['amenities'])) {
    $arr = is_string($listing['amenities']) ? json_decode($listing['amenities'], true) : $listing['amenities'];
    $amenities_str = implode("\n", (array)$arr);
}

function val(array $listing, string $key, string $default = ''): string {
    return htmlspecialchars($listing[$key] ?? $default);
}
function sel(array $listing, string $key, string $value): string {
    return ($listing[$key] ?? '') === $value ? 'selected' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= $id ? 'Edit' : 'Add' ?> Listing — Admin</title>
  <style>
    *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
    body { font-family: system-ui, sans-serif; background: #f0f2f5; color: #1a1d23; }
    .topbar { background: #1e3a5f; color: #fff; padding: .85rem 2rem; display: flex; justify-content: space-between; align-items: center; }
    .topbar h1 { font-size: 1.05rem; font-weight: 700; }
    .topbar a { color: rgba(255,255,255,.75); text-decoration: none; font-size: .88rem; }
    .topbar a:hover { color: #fff; }
    .main { max-width: 780px; margin: 2rem auto; padding: 0 1.5rem 4rem; }
    h2 { font-size: 1.25rem; margin-bottom: 1.5rem; }
    .card { background: #fff; border-radius: 10px; padding: 2rem; box-shadow: 0 1px 4px rgba(0,0,0,.08); }
    .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 1rem; }
    .form-group { margin-bottom: 1.1rem; }
    label { display: block; font-size: .85rem; font-weight: 600; color: #1e3a5f; margin-bottom: .35rem; }
    label .hint { font-weight: 400; color: #5a6272; }
    input, select, textarea { width: 100%; padding: .65rem .9rem; border: 1.5px solid #e2e6ec; border-radius: 7px; font-family: inherit; font-size: .93rem; }
    input:focus, select:focus, textarea:focus { outline: none; border-color: #2f5bd0; box-shadow: 0 0 0 3px rgba(47,91,208,.15); }
    textarea { resize: vertical; min-height: 100px; }
    .error-list { background: #fff0f0; border: 1px solid #fca5a5; color: #b91c1c; border-radius: 8px; padding: .75rem 1rem; margin-bottom: 1.25rem; font-size: .88rem; }
    .error-list li { margin-left: 1rem; }
    .form-actions { display: flex; gap: 1rem; margin-top: 1.5rem; }
    .btn { display: inline-flex; align-items: center; padding: .65rem 1.5rem; border-radius: 7px; font-size: .93rem; font-weight: 600; text-decoration: none; border: none; cursor: pointer; }
    .btn-primary { background: #2f5bd0; color: #fff; }
    .btn-primary:hover { background: #4a73e8; }
    .btn-secondary { background: #f0f2f5; color: #1a1d23; }
    .btn-secondary:hover { background: #e2e6ec; }
    .section-title { font-size: .78rem; font-weight: 700; text-transform: uppercase; letter-spacing: .08em; color: #5a6272; margin: 1.5rem 0 .75rem; padding-bottom: .4rem; border-bottom: 1px solid #e2e6ec; }
    .current-image { margin-top: .5rem; font-size: .82rem; color: #5a6272; }
    .current-image img { max-width: 120px; border-radius: 6px; margin-top: .35rem; border: 1px solid #e2e6ec; }
  </style>
</head>
<body>
  <div class="topbar">
    <h1>Cleveland Renter — Admin</h1>
    <div style="display:flex;gap:1.5rem;">
      <a href="dashboard.php">← Dashboard</a>
      <a href="logout.php">Log Out</a>
    </div>
  </div>

  <div class="main">
    <h2><?= $id ? 'Edit Listing' : 'Add New Listing' ?></h2>

    <?php if ($errors): ?>
    <div class="error-list">
      <strong>Please fix the following:</strong>
      <ul><?php foreach ($errors as $e) echo '<li>' . htmlspecialchars($e) . '</li>'; ?></ul>
    </div>
    <?php endif; ?>

    <div class="card">
      <form method="POST" action="save.php" enctype="multipart/form-data">
        <input type="hidden" name="csrf_token" value="<?= csrf_token() ?>">
        <input type="hidden" name="id" value="<?= $id ?>">

        <div class="section-title">Basic Info</div>

        <div class="form-row">
          <div class="form-group">
            <label for="name">Unit Name *</label>
            <input type="text" id="name" name="name" value="<?= val($listing, 'name') ?>" required placeholder="e.g. Elmwood Ave Upper">
          </div>
          <div class="form-group">
            <label for="slug">Slug <span class="hint">(auto-generated if blank)</span></label>
            <input type="text" id="slug" name="slug" value="<?= val($listing, 'slug') ?>" placeholder="e.g. elmwood-ave-upper">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="neighborhood">Neighborhood *</label>
            <select id="neighborhood" name="neighborhood" required>
              <option value="">— Select —</option>
              <option value="cleveland"        <?= sel($listing, 'neighborhood', 'cleveland') ?>>Cleveland</option>
              <option value="lakewood"         <?= sel($listing, 'neighborhood', 'lakewood') ?>>Lakewood</option>
              <option value="cleveland-heights" <?= sel($listing, 'neighborhood', 'cleveland-heights') ?>>Cleveland Heights</option>
            </select>
          </div>
          <div class="form-group">
            <label for="neighborhood_label">Neighborhood Label *</label>
            <input type="text" id="neighborhood_label" name="neighborhood_label" value="<?= val($listing, 'neighborhood_label') ?>" placeholder="e.g. Cleveland — Ohio City">
          </div>
        </div>

        <div class="section-title">Unit Details</div>

        <div class="form-row">
          <div class="form-group">
            <label for="beds">Bedrooms *</label>
            <input type="text" id="beds" name="beds" value="<?= val($listing, 'beds') ?>" required placeholder="e.g. 2 or Studio">
          </div>
          <div class="form-group">
            <label for="baths">Bathrooms *</label>
            <input type="text" id="baths" name="baths" value="<?= val($listing, 'baths', '1') ?>" required placeholder="e.g. 1">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="sqft">Square Footage</label>
            <input type="number" id="sqft" name="sqft" value="<?= val($listing, 'sqft') ?>" min="0" placeholder="e.g. 850">
          </div>
          <div class="form-group">
            <label for="rent">Monthly Rent ($) *</label>
            <input type="number" id="rent" name="rent" value="<?= val($listing, 'rent') ?>" required min="0" placeholder="e.g. 1150">
          </div>
        </div>

        <div class="form-row">
          <div class="form-group">
            <label for="status">Status *</label>
            <select id="status" name="status" required>
              <option value="available"    <?= sel($listing, 'status', 'available') ?>>Available</option>
              <option value="coming-soon"  <?= sel($listing, 'status', 'coming-soon') ?>>Coming Soon</option>
              <option value="rented"       <?= sel($listing, 'status', 'rented') ?>>Rented</option>
            </select>
          </div>
          <div class="form-group">
            <label for="sort_order">Display Order <span class="hint">(lower = first)</span></label>
            <input type="number" id="sort_order" name="sort_order" value="<?= val($listing, 'sort_order', '0') ?>" min="0">
          </div>
        </div>

        <div class="section-title">Description</div>

        <div class="form-group">
          <label for="blurb">Short Description *</label>
          <textarea id="blurb" name="blurb" required placeholder="2–3 sentences about the unit..."><?= val($listing, 'blurb') ?></textarea>
        </div>

        <div class="form-group">
          <label for="amenities">Amenities <span class="hint">(one per line)</span></label>
          <textarea id="amenities" name="amenities" rows="5" placeholder="Hardwood floors&#10;Off-street parking&#10;Heat included"><?= htmlspecialchars($amenities_str) ?></textarea>
        </div>

        <div class="section-title">Photo</div>

        <div class="form-group">
          <label for="image">Upload Photo <span class="hint">(JPG, PNG, WebP — max 5 MB)</span></label>
          <input type="file" id="image" name="image" accept="image/jpeg,image/png,image/webp">
          <?php if (!empty($listing['image_path'])): ?>
          <div class="current-image">
            Current image:<br>
            <img src="../<?= htmlspecialchars($listing['image_path']) ?>" alt="Current listing photo">
            <label style="margin-top:.4rem;display:flex;align-items:center;gap:.4rem;font-weight:400;">
              <input type="checkbox" name="remove_image" value="1"> Remove current image
            </label>
          </div>
          <?php endif; ?>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn btn-primary"><?= $id ? 'Save Changes' : 'Add Listing' ?></button>
          <a href="dashboard.php" class="btn btn-secondary">Cancel</a>
        </div>
      </form>
    </div>
  </div>
</body>
</html>

<?php
require_once 'auth.php';
require_once __DIR__ . '/../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: dashboard.php'); exit; }
verify_csrf();

$id = (int)($_POST['id'] ?? 0);

// Collect & validate
$fields = [
    'name'               => trim($_POST['name'] ?? ''),
    'neighborhood'       => trim($_POST['neighborhood'] ?? ''),
    'neighborhood_label' => trim($_POST['neighborhood_label'] ?? ''),
    'beds'               => trim($_POST['beds'] ?? ''),
    'baths'              => trim($_POST['baths'] ?? '1'),
    'sqft'               => strlen(trim($_POST['sqft'] ?? '')) ? (int)$_POST['sqft'] : null,
    'rent'               => (int)($_POST['rent'] ?? 0),
    'status'             => $_POST['status'] ?? 'available',
    'blurb'              => trim($_POST['blurb'] ?? ''),
    'sort_order'         => (int)($_POST['sort_order'] ?? 0),
];

// Slug
$raw_slug = trim($_POST['slug'] ?? '');
$slug = $raw_slug ?: slugify($fields['name']);

// Amenities → JSON
$amenities_raw = trim($_POST['amenities'] ?? '');
$amenities = $amenities_raw
    ? json_encode(array_values(array_filter(array_map('trim', explode("\n", $amenities_raw)))))
    : null;

$errors = [];
if (!$fields['name'])               $errors[] = 'Unit name is required.';
if (!$fields['neighborhood'])       $errors[] = 'Neighborhood is required.';
if (!$fields['neighborhood_label']) $errors[] = 'Neighborhood label is required.';
if (!$fields['beds'])               $errors[] = 'Bedrooms is required.';
if ($fields['rent'] <= 0)           $errors[] = 'A valid rent amount is required.';
if (!$fields['blurb'])              $errors[] = 'Short description is required.';
if (!in_array($fields['status'], ['available','coming-soon','rented'])) $errors[] = 'Invalid status.';

// Image upload
$image_path = null;
if (!empty($_FILES['image']['name'])) {
    $allowed_mime = ['image/jpeg', 'image/png', 'image/webp'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime  = $finfo->file($_FILES['image']['tmp_name']);
    $size  = $_FILES['image']['size'];
    $err   = $_FILES['image']['error'];

    if ($err !== UPLOAD_ERR_OK)        $errors[] = 'Upload error.';
    elseif (!in_array($mime, $allowed_mime)) $errors[] = 'Only JPG, PNG, and WebP images are allowed.';
    elseif ($size > 5 * 1024 * 1024)   $errors[] = 'Image must be under 5 MB.';
    else {
        $ext = ['image/jpeg' => 'jpg', 'image/png' => 'png', 'image/webp' => 'webp'][$mime];
        $filename   = $slug . '-' . time() . '.' . $ext;
        $upload_dir = __DIR__ . '/../assets/images/';
        if (!is_dir($upload_dir)) mkdir($upload_dir, 0755, true);
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $upload_dir . $filename)) {
            $errors[] = 'Failed to save uploaded image.';
        } else {
            $image_path = 'assets/images/' . $filename;
        }
    }
}

if ($errors) {
    $_SESSION['form_data']   = array_merge($fields, ['slug' => $slug]);
    $_SESSION['form_errors'] = $errors;
    header('Location: edit.php' . ($id ? '?id=' . $id : ''));
    exit;
}

// Check slug uniqueness
$stmt = $pdo->prepare("SELECT id FROM listings WHERE slug = ? AND id != ?");
$stmt->execute([$slug, $id ?: 0]);
if ($stmt->fetch()) {
    $_SESSION['form_data']   = array_merge($fields, ['slug' => $slug]);
    $_SESSION['form_errors'] = ['That slug is already in use. Choose a different one.'];
    header('Location: edit.php' . ($id ? '?id=' . $id : ''));
    exit;
}

if ($id) {
    // Existing listing — build update query
    $set = "name=:name, neighborhood=:neighborhood, neighborhood_label=:neighborhood_label,
            beds=:beds, baths=:baths, sqft=:sqft, rent=:rent, status=:status,
            blurb=:blurb, amenities=:amenities, slug=:slug, sort_order=:sort_order";
    $params = array_merge($fields, ['amenities' => $amenities, 'slug' => $slug, 'id' => $id]);

    // Handle image removal / replacement
    if (!empty($_POST['remove_image'])) {
        $set .= ', image_path=NULL';
    } elseif ($image_path) {
        $set .= ', image_path=:image_path';
        $params['image_path'] = $image_path;
    }

    $pdo->prepare("UPDATE listings SET $set WHERE id=:id")->execute($params);
    $_SESSION['flash'] = 'Listing updated.';
} else {
    $params = array_merge($fields, ['amenities' => $amenities, 'slug' => $slug]);
    if ($image_path) $params['image_path'] = $image_path;

    $cols = implode(',', array_keys($params));
    $vals = ':' . implode(', :', array_keys($params));
    $pdo->prepare("INSERT INTO listings ($cols) VALUES ($vals)")->execute($params);
    $_SESSION['flash'] = 'Listing added.';
}

header('Location: dashboard.php');
exit;

function slugify(string $str): string {
    $str = strtolower($str);
    $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
    return trim(preg_replace('/[\s-]+/', '-', $str), '-');
}

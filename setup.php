<?php
/**
 * One-time setup script. Visit /setup.php in your browser ONCE after uploading
 * to Bluehost. It creates the listings table and seeds it with starter data.
 * DELETE this file immediately after running it.
 */
require_once __DIR__ . '/includes/db.php';

$messages = [];

// Create listings table
$pdo->exec("CREATE TABLE IF NOT EXISTS listings (
    id               INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    slug             VARCHAR(100)  NOT NULL UNIQUE,
    name             VARCHAR(200)  NOT NULL,
    neighborhood     VARCHAR(50)   NOT NULL,
    neighborhood_label VARCHAR(120) NOT NULL,
    beds             VARCHAR(20)   NOT NULL,
    baths            VARCHAR(20)   NOT NULL DEFAULT '1',
    sqft             INT UNSIGNED  DEFAULT NULL,
    rent             INT UNSIGNED  NOT NULL,
    status           ENUM('available','coming-soon','rented') NOT NULL DEFAULT 'available',
    blurb            TEXT          DEFAULT NULL,
    amenities        JSON          DEFAULT NULL,
    image_path       VARCHAR(300)  DEFAULT NULL,
    sort_order       INT           NOT NULL DEFAULT 0,
    created_at       TIMESTAMP     DEFAULT CURRENT_TIMESTAMP,
    updated_at       TIMESTAMP     DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;");

$messages[] = '✅ Table created (or already exists).';

// Seed starter listings (skips if slug already exists)
$seed = [
    [
        'slug'               => 'elmwood-ave-upper',
        'name'               => 'Elmwood Ave Upper',
        'neighborhood'       => 'lakewood',
        'neighborhood_label' => 'Lakewood',
        'beds'               => '2',
        'baths'              => '1',
        'sqft'               => 850,
        'rent'               => 1150,
        'status'             => 'available',
        'blurb'              => 'Sun-filled upper unit in a classic Lakewood double. Hardwood floors, updated kitchen, off-street parking included. Walk to Detroit Ave shops and restaurants.',
        'amenities'          => json_encode(['Hardwood floors', 'Off-street parking', 'Heat included', 'Coin laundry in building']),
        'sort_order'         => 1,
    ],
    [
        'slug'               => 'cedar-rd-garden',
        'name'               => 'Cedar Rd Garden Apt',
        'neighborhood'       => 'cleveland-heights',
        'neighborhood_label' => 'Cleveland Heights',
        'beds'               => '1',
        'baths'              => '1',
        'sqft'               => 650,
        'rent'               => 895,
        'status'             => 'available',
        'blurb'              => 'Quiet garden-level apartment with large windows and a private patio. Lots of storage, updated bath, and a calm tree-lined street. Minutes from Coventry Village.',
        'amenities'          => json_encode(['Private patio', 'A/C', 'Water & trash included', 'On bus line']),
        'sort_order'         => 2,
    ],
    [
        'slug'               => 'w-25th-3bed',
        'name'               => 'W 25th 3-Bed Unit',
        'neighborhood'       => 'cleveland',
        'neighborhood_label' => 'Cleveland — Ohio City',
        'beds'               => '3',
        'baths'              => '1',
        'sqft'               => 1100,
        'rent'               => 1550,
        'status'             => 'coming-soon',
        'blurb'              => 'Spacious three-bedroom in the heart of Ohio City. Original woodwork, updated bathroom, in-unit laundry hookups. Steps from West Side Market and the Cuyahoga River.',
        'amenities'          => json_encode(['In-unit laundry hookup', 'Original hardwood', 'Street parking', 'Pet-friendly (ask)']),
        'sort_order'         => 3,
    ],
    [
        'slug'               => 'madison-ave-lower',
        'name'               => 'Madison Ave Lower',
        'neighborhood'       => 'lakewood',
        'neighborhood_label' => 'Lakewood',
        'beds'               => '1',
        'baths'              => '1',
        'sqft'               => 720,
        'rent'               => 1000,
        'status'             => 'available',
        'blurb'              => 'Cozy lower unit in a well-maintained Lakewood double. Bright living room with bay window, new appliances, and a fenced rear yard shared with one other unit.',
        'amenities'          => json_encode(['Bay window', 'New appliances', 'Fenced yard', 'Driveway parking']),
        'sort_order'         => 4,
    ],
    [
        'slug'               => 'fairmount-blvd-2bed',
        'name'               => 'Fairmount Blvd 2-Bed',
        'neighborhood'       => 'cleveland-heights',
        'neighborhood_label' => 'Cleveland Heights',
        'beds'               => '2',
        'baths'              => '1',
        'sqft'               => 900,
        'rent'               => 1200,
        'status'             => 'available',
        'blurb'              => 'Bright, well-kept apartment in a boutique building on leafy Fairmount Blvd. Separate dining room, renovated kitchen, and easy access to University Circle.',
        'amenities'          => json_encode(['Renovated kitchen', 'Dining room', 'Heat & water included', 'Near University Circle']),
        'sort_order'         => 5,
    ],
    [
        'slug'               => 'starkweather-studio',
        'name'               => 'Starkweather Ave Studio',
        'neighborhood'       => 'cleveland',
        'neighborhood_label' => 'Cleveland — Tremont',
        'beds'               => 'Studio',
        'baths'              => '1',
        'sqft'               => 520,
        'rent'               => 775,
        'status'             => 'available',
        'blurb'              => 'Efficient and stylish studio in Tremont. High ceilings, exposed brick, and a walk-in closet. Walking distance to galleries and cafés.',
        'amenities'          => json_encode(['Exposed brick', 'High ceilings', 'Walk-in closet', 'Street parking']),
        'sort_order'         => 6,
    ],
];

$stmt = $pdo->prepare("INSERT IGNORE INTO listings
    (slug, name, neighborhood, neighborhood_label, beds, baths, sqft, rent, status, blurb, amenities, sort_order)
    VALUES (:slug, :name, :neighborhood, :neighborhood_label, :beds, :baths, :sqft, :rent, :status, :blurb, :amenities, :sort_order)");

foreach ($seed as $row) {
    $stmt->execute($row);
}

$messages[] = '✅ Seed listings inserted (existing slugs skipped).';
$messages[] = '⚠️  DELETE this file (setup.php) now!';
?>
<!DOCTYPE html>
<html lang="en">
<head><meta charset="UTF-8"><title>Setup — Cleveland Renter</title>
<style>body{font-family:sans-serif;max-width:600px;margin:3rem auto;padding:0 1rem;}
li{margin:.5rem 0;font-size:1.1rem;}</style>
</head>
<body>
<h1>Setup complete</h1>
<ul><?php foreach ($messages as $m) echo '<li>' . htmlspecialchars($m) . '</li>'; ?></ul>
<p><strong>Delete <code>setup.php</code> from your server now.</strong></p>
</body>
</html>

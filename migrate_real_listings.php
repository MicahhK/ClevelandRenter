<?php
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/includes/db.php';

// Clear all existing placeholder listings
$pdo->exec("DELETE FROM listings");
$pdo->exec("ALTER TABLE listings AUTO_INCREMENT = 1");

$listings = [
    [
        'name'               => '16700 Clifton Blvd Unit 3',
        'neighborhood'       => 'lakewood',
        'neighborhood_label' => 'Lakewood',
        'beds'               => '?',
        'baths'              => '?',
        'sqft'               => null,
        'rent'               => 1000,
        'status'             => 'available',
        'blurb'              => 'Available now on Clifton Blvd in Lakewood. View full details on Zillow.',
        'zillow_url'         => 'https://www.zillow.com/homedetails/16700-Clifton-Blvd-3-Lakewood-OH-44107/2082147069_zpid/',
        'sort_order'         => 1,
    ],
    [
        'name'               => '2162 Maplewood Rd',
        'neighborhood'       => 'cleveland-heights',
        'neighborhood_label' => 'Cleveland Heights',
        'beds'               => '?',
        'baths'              => '?',
        'sqft'               => null,
        'rent'               => 2000,
        'status'             => 'coming-soon',
        'blurb'              => 'Coming available June 2027 on Maplewood Rd in Cleveland Heights. View full details on Zillow.',
        'zillow_url'         => 'https://www.zillow.com/homedetails/2162-Maplewood-Rd-Cleveland-Heights-OH-44118/33660138_zpid/',
        'sort_order'         => 2,
    ],
];

foreach ($listings as $l) {
    $slug = slugify($l['name']);
    $pdo->prepare("
        INSERT INTO listings (name, neighborhood, neighborhood_label, beds, baths, sqft, rent, status, blurb, zillow_url, sort_order, slug)
        VALUES (:name, :neighborhood, :neighborhood_label, :beds, :baths, :sqft, :rent, :status, :blurb, :zillow_url, :sort_order, :slug)
    ")->execute(array_merge($l, ['slug' => $slug]));
}

echo '<pre>✅ Done! ' . count($listings) . ' real listings inserted. DELETE this file from Bluehost now.</pre>';

function slugify(string $str): string {
    $str = strtolower($str);
    $str = preg_replace('/[^a-z0-9\s-]/', '', $str);
    return trim(preg_replace('/[\s-]+/', '-', $str), '-');
}

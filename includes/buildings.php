<?php
/**
 * The buildings Cleveland Renter manages.
 *
 * Buildings live here rather than in the database because the set changes
 * rarely — adding one is a code edit, not an admin task. Individual units
 * stay in the `listings` table and point back here via their `building`
 * column, which holds one of the slugs below.
 *
 * 'image' is a path relative to the site root, or null to fall back to the
 * same gradient placeholder the compact listing cards use.
 */

const BUILDINGS = [
    '9414-clifton' => [
        'name'       => '9414 Clifton Blvd',
        'address'    => '9414 Clifton Blvd, Cleveland, OH 44102',
        'city'       => 'Cleveland',
        'blurb'      => 'On the Cleveland end of Clifton Blvd, minutes from Edgewater Park and the Gordon Square Arts District, with a quick run downtown along the Shoreway.',
        'image'      => null,
        'sort_order' => 1,
    ],
    '16700-clifton' => [
        'name'       => '16700 Clifton Blvd',
        'address'    => '16700 Clifton Blvd, Lakewood, OH 44107',
        'city'       => 'Lakewood',
        'blurb'      => 'West Lakewood on Clifton Blvd, close to Lakewood Park and the shops and restaurants along Detroit Ave.',
        'image'      => null,
        'sort_order' => 2,
    ],
    'wagar' => [
        'name'       => '1553 & 1555 Wagar Ave',
        'address'    => '1553 & 1555 Wagar Ave, Lakewood, OH 44107',
        'city'       => 'Lakewood',
        'blurb'      => 'A quiet residential street in central Lakewood, walking distance to Madison Ave and an easy trip to the Gold Coast.',
        'image'      => null,
        'sort_order' => 3,
    ],
    '2052-wascana' => [
        'name'       => '2052 Wascana Ave',
        'address'    => '2052 Wascana Ave, Lakewood, OH 44107',
        'city'       => 'Lakewood',
        'blurb'      => 'A tree-lined side street in Lakewood, close to Madison Park and a short drive from downtown Cleveland.',
        'image'      => null,
        'sort_order' => 4,
    ],
    '2162-maplewood' => [
        'name'       => '2162 Maplewood Rd',
        'address'    => '2162 Maplewood Rd, Cleveland Heights, OH 44118',
        'city'       => 'Cleveland Heights',
        'blurb'      => 'Cleveland Heights near Cedar-Lee, with its theatre, coffee shops and restaurants, and a straight shot to University Circle.',
        'image'      => null,
        'sort_order' => 5,
    ],
];

/**
 * Every building, in display order, each with its slug attached as 'slug'.
 *
 * @return array<int, array<string, mixed>>
 */
function all_buildings(): array {
    $buildings = [];
    foreach (BUILDINGS as $slug => $b) {
        $buildings[] = $b + ['slug' => $slug];
    }
    usort($buildings, fn($a, $b) => $a['sort_order'] <=> $b['sort_order']);
    return $buildings;
}

/**
 * One building by slug, with its slug attached, or null if the slug is unknown.
 *
 * @return array<string, mixed>|null
 */
function get_building(string $slug): ?array {
    if (!isset(BUILDINGS[$slug])) return null;
    return BUILDINGS[$slug] + ['slug' => $slug];
}

/**
 * The valid building slugs — used to validate admin input.
 *
 * @return array<int, string>
 */
function building_slugs(): array {
    return array_keys(BUILDINGS);
}

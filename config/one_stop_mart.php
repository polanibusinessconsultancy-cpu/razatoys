<?php

return [
    /*
    |--------------------------------------------------------------------------
    | One Stop Mart Presentation Configuration
    |--------------------------------------------------------------------------
    |
    | Stores only presentation metadata for theme customization and layout.
    | Prices, customer records, and orders are retrieved dynamically.
    |
    */

    'featured_source_keys' => [
        'OSM-TY-001',
        'OSM-KW-003',
        'OSM-PF-005',
        'OSM-TY-002',
        'OSM-KW-004',
        'OSM-KW-006',
        'OSM-EL-007',
        'OSM-FS-008',
    ],

    'new_arrival_source_keys' => [
        'OSM-TY-101',
        'OSM-TY-102',
        'OSM-KW-103',
        'OSM-KW-104',
        'OSM-PF-105',
        'OSM-KW-106',
        'OSM-EL-107',
        'OSM-TY-108',
        'OSM-PF-109',
        'OSM-KW-110',
    ],

    'deal_source_keys' => [
        'OSM-TY-001',
        'OSM-TY-002',
        'OSM-KW-003',
        'OSM-KW-004',
        'OSM-PF-005',
        'OSM-KW-006',
    ],

    'category_display_order' => [
        'toys',
        'kitchenware',
        'perfumes',
        'electronics',
        'fashion',
        'home-living',
        'sports',
    ],

    'hero' => [
        'badge' => 'WELCOME TO ONE STOP MART',
        'title_normal' => 'Everything You Need,',
        'title_highlight' => 'All in One Place',
        'description' => 'Explore quality products across toys, kitchenware, perfumes, household essentials, and more.',
        'image_path' => 'one-stop-mart/images/hero-banner.png',
    ],

    'service_benefits' => [
        [
            'title' => 'Free Delivery',
            'desc' => 'On orders above Rs. 10,000',
            'icon' => 'truck',
        ],
        [
            'title' => 'Easy Returns',
            'desc' => 'Within 7 days',
            'icon' => 'rotate-left',
        ],
        [
            'title' => '100% Secure',
            'desc' => 'Checkout Payments',
            'icon' => 'lock',
        ],
    ],
];

<?php
/**
 * Supply the basis for the navbar as an array.
 */
return [
    // Use for styling the menu
    "wrapper" => null,
    "class" => "my-navbar rm-default rm-desktop",
 
    // Here comes the menu items
    "items" => [
        [
            "text" => "Hem",
            "url" => "",
            "title" => "Första sidan, börja här.",
        ],
        [
            "text" => "Redovisning",
            "url" => "redovisning",
            "title" => "Redovisningstexter från kursmomenten.",
            "submenu" => [
                "items" => [
                    [
                        "text" => "Kmom01",
                        "url" => "redovisning/kmom01",
                        "title" => "Redovisning för kmom01.",
                    ],
                    [
                        "text" => "Kmom02",
                        "url" => "redovisning/kmom02",
                        "title" => "Redovisning för kmom02.",
                    ],
                ],
            ],
        ],
        [
            "text" => "Om",
            "url" => "om",
            "title" => "Om denna webbplats.",
        ],
        [
            "text" => "IP-validator",
            "url" => "verify-ip",
            "title" => "Validate IP Addresses",
            "submenu" => [
                "items" => [
                    [
                        "text" => "Standard Verification",
                        "url" => "verify-ip",
                        "title" => "Standard validation.",
                    ],
                    [
                        "text" => "JSON Verification",
                        "url" => "verify-ip-json",
                        "title" => "JSON validation",
                    ],
                ],
            ],
        ],
        [
            "text" => "IP-geo-locator",
            "url" => "ip-geo-locator",
            "title" => "IP with geolocation",
            "submenu" => [
                "items" => [
                    [
                        "text" => "Kris IP-Geolocator",
                        "url" => "ip-geo-locator",
                        "title" => "IP-Geolocator.",
                    ],
                    [
                        "text" => "Kris IP-Geolocator API(JSON)",
                        "url" => "ip-geo-locator-api",
                        "title" => "IP-Geolocator API(JSON).",
                    ],
                ],
            ],
        ],
        [
            "text" => "Styleväljare",
            "url" => "style",
            "title" => "Välj stylesheet.",
        ],
        [
            "text" => "Verktyg",
            "url" => "verktyg",
            "title" => "Verktyg och möjligheter för utveckling.",
        ],
    ],
];

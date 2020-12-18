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
                    [
                        "text" => "Kmom03",
                        "url" => "redovisning/kmom03",
                        "title" => "Redovisning för kmom03.",
                    ],
                    [
                        "text" => "Kmom04",
                        "url" => "redovisning/kmom04",
                        "title" => "Redovisning för kmom04.",
                    ],
                    [
                        "text" => "Kmom05",
                        "url" => "redovisning/kmom05",
                        "title" => "Redovisning för kmom05.",
                    ],
                    [
                        "text" => "Kmom06",
                        "url" => "redovisning/kmom06",
                        "title" => "Redovisning för kmom06.",
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
            "text" => "Weather service",
            "url" => "weather",
            "title" => "Weather service",
            "submenu" => [
                "items" => [
                    [
                        "text" => "Weather service",
                        "url" => "weather",
                        "title" => "IP-Geolocator.",
                    ],
                    [
                        "text" => "Weather service API(JSON)",
                        "url" => "weather-api",
                        "title" => "Weather service API(JSON).",
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

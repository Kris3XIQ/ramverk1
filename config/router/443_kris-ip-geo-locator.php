<?php
/**
 * Load the stylechooser as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Kris ip verification with geo-location.",
            "mount" => "ip-geo-locator",
            "handler" => "\Anax\Controller\KrisIpGeoLocatorController",
        ],
    ]
];

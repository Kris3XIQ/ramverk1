<?php
/**
 * Load the stylechooser as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Kris IP-Geolocator API.",
            "mount" => "ip-geo-locator-api",
            "handler" => "\Anax\Controller\KrisIpGeoLocatorApiController",
        ],
    ]
];

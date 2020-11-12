<?php
/**
 * Load the stylechooser as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Verify IP API controller.",
            "mount" => "verify-ip-api",
            "handler" => "\Anax\Controller\KrisVerifyIpApiController",
        ],
    ]
];

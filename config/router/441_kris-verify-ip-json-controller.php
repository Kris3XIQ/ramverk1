<?php
/**
 * Load the stylechooser as a controller class.
 */
return [
    "routes" => [
        [
            "info" => "Kris controller.",
            "mount" => "verify-ip-json",
            "handler" => "\Anax\Controller\KrisVerifyIpJsonController",
        ],
    ]
];

<?php
/**
 * Configuration for DI container, set API key.
 */
return [
    "services" => [
        "api-service" => [
            "shared" => true,
            "active" => false,
            "callback" => function () {
                $service = new \Anax\Service\APIService();
                $service->setDi($this);

                // Load configuration file(s)
                $cfg = $this->get("configuration");
                $config = $cfg->load("api-keys");
                $settings = $config["config"] ?? null;

                if ($settings["keys"] ?? null) {
                    $service->setKeyChain($settings["keys"]);
                }

                return $service;
            }
        ],
    ],
];

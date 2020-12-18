<?php

namespace Anax\Service;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * API Service class
 *
 * @SuppressWarnings(PHPMD)
 */
class APIService
{
    use ContainerInjectableTrait;

    private $keyChain = null;
    private $service = null;
    private $services = null;

    public function setKeyChain(array $key) : void
    {
        $this->keyChain = $key;
    }

    public function setServiceToLoad(string $service) : void
    {
        $this->service = $service;
    }

    public function getKeyToService()
    {
        $service = $this->service;
        $keyChain = $this->keyChain;
        // if (array_key_exists($service, $keyChain)) {
        return $keyChain[$service];
        // }
    }
}

<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\Geolocator\Geolocator;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class KrisIpGeoLocatorController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * @return object
     */
    public function indexAction() : object
    {
        // Setting up variables.
        $title = "IP-Geo-locator";
        $page = $this->di->get("page");

        // Setting up geolocator and setting API-key
        $geolocator = new Geolocator();
        $apiKey = include(dirname(__FILE__).'/../Config/geo-config.php');
        $geolocator->setApiKey($apiKey);

        $ipInfo = $_SESSION["ipInfo"] ?? null;

        $data = [
            "message" => $message ?? null,
            "ip" => $ipInfo["ip"] ?? null,
            "type" => $ipInfo["type"] ?? null,
            "user_host" => $geolocator->getUserHost() ?? null,
            "result_host" => $geolocator->getResultHost($ipInfo) ?? null,
            // "user_host" => null,
            // "result_host" => null,
            "country_name" => $ipInfo["country_name"] ?? null,
            "flag" => $ipInfo["location"]["country_flag"] ?? null,
            "region_name" => $ipInfo["region_name"] ?? null,
            "latitude" => $ipInfo["latitude"] ?? null,
            "longitude" => $ipInfo["longitude"] ?? null
        ];

        $page->add("ip/ip-geo-locator", $data);
            
        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This handles POST request
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * @return object
     */
    public function indexActionPost() : object
    {
        // Set variables
        $response = $this->di->get("response");
        $request = $this->di->get("request");

        // Setting up geolocator and setting API-key.
        $geolocator = new Geolocator();
        $apiKey = include(dirname(__FILE__).'/../Config/geo-config.php');
        $geolocator->setApiKey($apiKey);
        
        // Grab post variable and call Geolocator with appropriate functions.
        $ipAddress = $request->getPost("validateIp");
        $ipInfo = $geolocator->ipTrackWithGeolocation($ipAddress);

        // Set session variable 'ipInfo' to contain results from API-Call
        $_SESSION["ipInfo"] = $ipInfo;
    
        // Return di->response object to redirect to ip-geo-locator.
        return $response->redirect("ip-geo-locator");
    }
}

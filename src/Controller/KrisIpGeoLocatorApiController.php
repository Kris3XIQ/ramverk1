<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Anax\Geolocator\GeolocatorApi;

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
class KrisIpGeoLocatorApiController implements ContainerInjectableInterface
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
        $title = "IP-Geo-locator API";
        $page = $this->di->get("page");

        // Setting up geolocator and setting API-key
        $geolocator = new GeolocatorApi();
        $apiKey = include(dirname(__FILE__).'/../Config/geo-config.php');
        $geolocator->setApiKey($apiKey);
        $ipInfo = [];

        $data = [
            "message" => $message ?? null,
            "ip" => $ipInfo["ip"] ?? null,
            "type" => $ipInfo["type"] ?? null,
            "user_host" => $geolocator->getUserHost() ?? null,
            "result_host" => $geolocator->getResultHost($ipInfo) ?? null,
            "country_name" => $ipInfo["country_name"] ?? null,
            "region_name" => $ipInfo["region_name"] ?? null,
            "latitude" => $ipInfo["latitude"] ?? null,
            "longitude" => $ipInfo["longitude"] ?? null
        ];

        $page->add("ip/ip-geo-locator-api", $data);
            
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
    public function indexActionPost() : array
    {
        // Setting variables, geolocator and API-key.
        $request = $this->di->get("request");
        $geolocator = new GeolocatorApi();
        $apiKey = include(dirname(__FILE__).'/../Config/geo-config.php');
        $geolocator->setApiKey($apiKey);

        // if ($request->getPost("submit")) {
        if ($request->getPost("validateIp") || $request->getPost("validateDbwebb") ||
            $request->getPost("validateGoogle") || $request->getPost("validateNone")) {
            $ipAddress = $geolocator->validateRoutes($request->getPost());
            $ipInfo = $geolocator->ipTrackWithGeolocationAPI($ipAddress);
        } else {
            $body = $this->di->get("request")->getBodyAsJson();
            // Getting verification on $body and making sure
            // it has the key "ip".
            $verification = $geolocator->getVerificationOnBody($body);
            if ($verification == true) {
                $ipAddress = $body["ip"];
                $ipInfo = $geolocator->ipTrackWithGeolocationAPI($ipAddress);
            } else {
                // Sent in an array but not using "ip" as key.
                if (is_array($body)) {
                    $ipAddress = array_key_first($body);
                // Alternatively fetching the API using only a string,
                // example "194.47.159.9", also valid.
                } else {
                    $ipAddress = $body;
                }
                $ipInfo = $geolocator->ipTrackWithGeolocationAPI($ipAddress);
            }
        }

        // Grab post variable and call Geolocator with appropriate functions.

        // Set session variable 'ipInfo' to contain results from API-Call
        $json = [
            "ip" => $ipInfo["ip"] ?? null,
            "type" => $ipInfo["type"] ?? null,
            "user_host" => $geolocator->getUserHost() ?? null,
            "result_host" => $geolocator->getResultHost($ipInfo) ?? null,
            "country_name" => $ipInfo["country_name"] ?? null,
            "region_name" => $ipInfo["region_name"] ?? null,
            "latitude" => $ipInfo["latitude"] ?? null,
            "longitude" => $ipInfo["longitude"] ?? null
        ];
    
        // Return di->response object to redirect to ip-geo-locator.
        return [$json];
    }
}

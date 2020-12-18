<?php

namespace Kris3XIQ\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;
use Kris3XIQ\Service\APIService;
use Kris3XIQ\Weather\Weather;

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
class KrisWeatherApiController implements ContainerInjectableInterface
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
        $title = "Weather service";
        $page = $this->di->get("page");

        // Get services to load and their API-keys.
        $service = $this->di->get("api-service");
        $service->setServiceToLoad("openweathermap");
        $owmApiKey = $service->getKeyToService();
        $service->setServiceToLoad("ipstack");
        $ipsApiKey = $service->getKeyToService("ipstack");

        // Setting up Weather
        $weather = new Weather();
        $weather->setKeyChain(array("openweathermap" => $owmApiKey, "ipstack" => $ipsApiKey));
        
        // Session variables
        $statusCode = $_SESSION["statusCode"] ?? null;

        // Add data to the view
        $data = [
            "statusCode" => $statusCode ?? null,
        ];

        // Grab a view
        $page->add("weather/weather-service-api", $data);
            
        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This handles POST request
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     */
    public function indexActionPost()
    {
        // Set variables
        $response = $this->di->get("response");
        $request = $this->di->get("request");
        $service = $this->di->get("api-service");

        // Grab post variables
        $external = false;
        $input = $request->getPost("input");
        if (!$input) {
            $external = true;
            try {
                $body = $this->di->get("request")->getBodyAsJson();
            } catch (\Exception $e) {
                $data = [
                    "status_code" => 404,
                    "message" => "Something went wrong, read the API-documentation",
                    "details" => "Looks like your input was empty"
                ];
                return [$data];
            }
            if (is_array($body)) {
                if (!array_key_exists("input", $body)) {
                    $data = [
                        "status_code" => 404,
                        "message" => "Something went wrong, read the API-documentation",
                        "details" => "Make sure to use the key 'input', read documentation for examples",
                    ];
                    return [$data];
                }
            }
            $input = $body["input"];
        }

        // If user didnt input anything
        if ($input == "") {
            $data = [
                "status_code" => 404,
                "message" => "Something went wrong, read the API-documentation",
                "details" => "Looks like your input was empty"
            ];
            return [$data];
        }

        // Setting up Weather
        $weather = new Weather();
        $service->setServiceToLoad("openweathermap");
        $owmApiKey = $service->getKeyToService();
        $service->setServiceToLoad("ipstack");
        $ipsApiKey = $service->getKeyToService("ipstack");
        $weather->setKeyChain(array("openweathermap" => $owmApiKey, "ipstack" => $ipsApiKey));
        
        $filter = $weather->isIpAddress($input);
        if ($filter) {
            $loc = $weather->ipTrackWithGeolocation($input);
            $location = $loc["city"] . ", " . $loc["country_code"];
            $weatherCurrentJSON = $weather->getWeatherCurrentFromAPI($location);
            $weatherHistoryJSON = $weather->getWeatherHistoryFromAPI($loc["latitude"], $loc["longitude"]);
        } else {
            $weather->setApiKey($owmApiKey);
            $isVerified = $weather->verifyLocation($input);
            if ($isVerified != "404") {
                $weatherCurrentJSON = $weather->getWeatherCurrentFromAPI($input);
                $lon = $weatherCurrentJSON["coord"]["lon"];
                $lat = $weatherCurrentJSON["coord"]["lat"];
                $weatherHistoryJSON = $weather->getWeatherHistoryFromAPI($lat, $lon);
            } else {
                $data = [
                    "status_code" => 404,
                    "message" => "Something went wrong, read the API-documentation",
                    "details" => "Looks like your search for '$input' didnt match anything"
                ];
                return [$data];
            }
        }

        if ($weatherHistoryJSON) {
            $dataJSON = $weather->convertToJSON($weatherCurrentJSON, $weatherHistoryJSON);
            $_SESSION["statusCode"] = null;
        }

        return [$dataJSON];
    }
}

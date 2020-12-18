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
class KrisWeatherController implements ContainerInjectableInterface
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
        $weatherCurrentJSON = $_SESSION["weatherCurrentJSON"] ?? null;
        $weatherHistoryJSON = $_SESSION["weatherHistoryJSON"] ?? null;

        if ($weatherHistoryJSON) {
            $pastOne = json_decode($weatherHistoryJSON["past_days"]["past_01"], true);
            $pastTwo = json_decode($weatherHistoryJSON["past_days"]["past_02"], true);
            $pastThree = json_decode($weatherHistoryJSON["past_days"]["past_03"], true);
            $pastFour = json_decode($weatherHistoryJSON["past_days"]["past_04"], true);
            $pastFive = json_decode($weatherHistoryJSON["past_days"]["past_05"], true);
        }

        $data = [
            "key" => $keyToService ?? null,
            "weatherCurrentJSON" => $weatherCurrentJSON ?? null,
            "weatherHistoryJSON" => $weatherHistoryJSON ?? null,
            "pastOne" => $pastOne ?? null,
            "pastTwo" => $pastTwo ?? null,
            "pastThree" => $pastThree ?? null,
            "pastFour" => $pastFour ?? null,
            "pastFive" => $pastFive ?? null,
            "latitude" => $weatherCurrentJSON["coord"]["lat"] ?? null,
            "longitude" => $weatherCurrentJSON["coord"]["lon"] ?? null,
        ];

        $page->add("weather/weather-service", $data);
            
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
        $service = $this->di->get("api-service");

        // Grab post variables
        $input = $request->getPost("input");

        // If user didnt input anything, redirect back.
        if ($input == "") {
            $data = [
                "cod" => 404,
            ];
            $_SESSION["weatherCurrentJSON"] = $data;
            return $response->redirect("weather");
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
                    "cod" => 404,
                ];
                $_SESSION["weatherCurrentJSON"] = $data;
                $_SESSION["weatherHistoryJSON"] = null;
                return $response->redirect("weather");
            }
        }

        // Set session variables
        $_SESSION["weatherCurrentJSON"] = $weatherCurrentJSON;
        $_SESSION["weatherHistoryJSON"] = $weatherHistoryJSON;
    
        // Return di->response object to redirect to ip-geo-locator.
        return $response->redirect("weather");
    }
}

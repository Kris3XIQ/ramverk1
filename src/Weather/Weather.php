<?php

// namespace Anax\Weather;
namespace Kris3XIQ\Weather;

/**
 * A model class retrieving data from an external server.
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Weather
{
    protected $key;
    protected $keyChain;

    /**
     * Set API-key value.
     *
     * @return void
     */
    public function setApiKey(string $apiKey)
    {
        $this->key = $apiKey;
    }

    /**
     * Set a new API-keychain (multiple API-keys), if you
     * need to fetch multiple API's.
     *
     * @return void
     */
    public function setKeyChain(array $keyChain)
    {
        $this->keyChain["keys"] = $keyChain;
    }

    public function verifyLocation(string $input)
    {
        $apiKey;
        $keyChain = $this->keyChain;
        if (array_key_exists("keys", $keyChain)) {
            $apiKey = $keyChain["keys"]["openweathermap"];
        }
        $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q=$input&units=metric&appid=$apiKey";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);

        $data = curl_exec($ch);

        curl_close($ch);
    
        $status = json_decode($data, true);

        return $status["cod"];
    }

    public function getWeatherCurrentFromAPI(string $input)
    {
        $apiKey;
        $keyChain = $this->keyChain;
        if (array_key_exists("keys", $keyChain)) {
            $apiKey = $keyChain["keys"]["openweathermap"];
        }
        $apiUrl = "https://api.openweathermap.org/data/2.5/weather?q=$input&units=metric&appid=$apiKey";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);

        $data = curl_exec($ch);

        curl_close($ch);
    
        $weatherInfo = json_decode($data, true);

        return $weatherInfo;
    }

    public function getWeatherHistoryFromAPI($lat, $lon)
    {
        $apiKey;
        $keyChain = $this->keyChain;
        if (array_key_exists("keys", $keyChain)) {
            $apiKey = $keyChain["keys"]["openweathermap"];
        }
    
        // Create all cURL resources
        $dayOne = curl_init();
        $dayTwo = curl_init();
        $dayThree = curl_init();
        $dayFour = curl_init();
        $dayFive = curl_init();

        // Set dates
        $dayMinusOne = mktime(0, 0, 0, date("m"), date("d"), date("Y"));
        $dayMinusTwo = mktime(0, 0, 0, date("m"), date("d")-1, date("Y"));
        $dayMinusThree = mktime(0, 0, 0, date("m"), date("d")-2, date("Y"));
        $dayMinusFour = mktime(0, 0, 0, date("m"), date("d")-3, date("Y"));
        $dayMinusFive = mktime(0, 0, 0, date("m"), date("d")-4, date("Y"));

        // set URL and other appropriate options
        curl_setopt($dayOne, CURLOPT_URL, "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=$lat&lon=$lon&dt=$dayMinusOne&units=metric&appid=$apiKey");
        curl_setopt($dayOne, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($dayTwo, CURLOPT_URL, "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=$lat&lon=$lon&dt=$dayMinusTwo&units=metric&appid=$apiKey");
        curl_setopt($dayTwo, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($dayThree, CURLOPT_URL, "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=$lat&lon=$lon&dt=$dayMinusThree&units=metric&appid=$apiKey");
        curl_setopt($dayThree, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($dayFour, CURLOPT_URL, "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=$lat&lon=$lon&dt=$dayMinusFour&units=metric&appid=$apiKey");
        curl_setopt($dayFour, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($dayFive, CURLOPT_URL, "https://api.openweathermap.org/data/2.5/onecall/timemachine?lat=$lat&lon=$lon&dt=$dayMinusFive&units=metric&appid=$apiKey");
        curl_setopt($dayFive, CURLOPT_RETURNTRANSFER, true);

        // Create the multiple cURL handle
        $mh = curl_multi_init();

        // Add the five handles
        curl_multi_add_handle($mh, $dayOne);
        curl_multi_add_handle($mh, $dayTwo);
        curl_multi_add_handle($mh, $dayThree);
        curl_multi_add_handle($mh, $dayFour);
        curl_multi_add_handle($mh, $dayFive);

        do {
            $status = curl_multi_exec($mh, $active);
            if ($active) {
                curl_multi_select($mh);
            }
        } while ($active && $status == CURLM_OK);

        curl_multi_remove_handle($mh, $dayOne);
        curl_multi_remove_handle($mh, $dayTwo);
        curl_multi_remove_handle($mh, $dayThree);
        curl_multi_remove_handle($mh, $dayFour);
        curl_multi_remove_handle($mh, $dayFive);
        curl_multi_close($mh);

        $data;
        $response1 = curl_multi_getcontent($dayOne);
        $response2 = curl_multi_getcontent($dayTwo);
        $response3 = curl_multi_getcontent($dayThree);
        $response4 = curl_multi_getcontent($dayFour);
        $response5 = curl_multi_getcontent($dayFive);
        $data = [
            "cod" => 200,
            "past_days" => [
                "past_01" => $response1,
                "past_02" => $response2,
                "past_03" => $response3,
                "past_04" => $response4,
                "past_05" => $response5
            ],
        ];
        
        return $data;
    }

    public function ipTrackWithGeolocation(string $input)
    {
        $apiKey;
        $keyChain = $this->keyChain;
        if (array_key_exists("keys", $keyChain)) {
            $apiKey = $keyChain["keys"]["ipstack"];
        }
        $apiUrl = "http://api.ipstack.com/$input?access_key=$apiKey";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);

        $data = curl_exec($ch);

        curl_close($ch);
    
        $data = json_decode($data, true);

        return $data;
    }

    /**
     * Confirm validity of ipAddress to be either
     * IPv4 or IPv6.
     *
     * @return string
     */
    public function isIpAddress($ip)
    {
        $ipv4 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        $ipv6 = filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        if ($ipv4 || $ipv6) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Converts current weather- and history data to JSON
     *
     * @return array
     */
    public function convertToJSON($currentWeather, $historyWeather)
    {
        if ($historyWeather) {
            $pastOne = json_decode($historyWeather["past_days"]["past_01"], true);
            $pastTwo = json_decode($historyWeather["past_days"]["past_02"], true);
            $pastThree = json_decode($historyWeather["past_days"]["past_03"], true);
            $pastFour = json_decode($historyWeather["past_days"]["past_04"], true);
            $pastFive = json_decode($historyWeather["past_days"]["past_05"], true);
        }

        $dataJSON = [
            "status code" => $currentWeather["cod"],
            "name" => $currentWeather["name"] . ", " . $currentWeather["sys"]["country"],
            "current_weather" => [
                "main" => $currentWeather["weather"][0]["main"],
                "description" => $currentWeather["weather"][0]["description"],
                "temperature" => $currentWeather["main"]["temp"],
                "feels_like" =>  $currentWeather["main"]["feels_like"],
            ],
            "yesterday" => [
                "date" => gmdate('r', $pastOne["hourly"][14]["dt"]),
                "main" => $pastOne["hourly"][14]["weather"][0]["main"],
                "description" => $pastOne["hourly"][14]["weather"][0]["description"],
                "temperature" => $pastOne["hourly"][14]["temp"],
                "feels_like" => $pastOne["hourly"][14]["feels_like"],
            ],
            "2_days_ago" => [
                "date" => gmdate('r', $pastTwo["hourly"][14]["dt"]),
                "main" => $pastTwo["hourly"][14]["weather"][0]["main"],
                "description" => $pastTwo["hourly"][14]["weather"][0]["description"],
                "temperature" => $pastTwo["hourly"][14]["temp"],
                "feels_like" => $pastTwo["hourly"][14]["feels_like"],
            ],
            "3_days_ago" => [
                "date" => gmdate('r', $pastThree["hourly"][14]["dt"]),
                "main" => $pastThree["hourly"][14]["weather"][0]["main"],
                "description" => $pastThree["hourly"][14]["weather"][0]["description"],
                "temperature" => $pastThree["hourly"][14]["temp"],
                "feels_like" => $pastThree["hourly"][14]["feels_like"],
            ],
            "4_days_ago" => [
                "date" => gmdate('r', $pastFour["hourly"][14]["dt"]),
                "main" => $pastFour["hourly"][14]["weather"][0]["main"],
                "description" => $pastFour["hourly"][14]["weather"][0]["description"],
                "temperature" => $pastFour["hourly"][14]["temp"],
                "feels_like" => $pastFour["hourly"][14]["feels_like"],
            ],
            "5_days_ago" => [
                "date" => gmdate('r', $pastFive["hourly"][14]["dt"]),
                "main" => $pastFive["hourly"][14]["weather"][0]["main"],
                "description" => $pastFive["hourly"][14]["weather"][0]["description"],
                "temperature" => $pastFive["hourly"][14]["temp"],
                "feels_like" => $pastFive["hourly"][14]["feels_like"],
            ]
        ];

        return $dataJSON;
    }
}

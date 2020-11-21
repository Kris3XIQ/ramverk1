<?php

namespace Anax\Geolocator;

/**
 * A model class retrieving data from an external server.
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class Geolocator
{
    protected $key;

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
     * Get API-key value.
     *
     * @return string
     */
    public function getApiKey()
    {
        return $this->key;
    }

    public function ipTrackWithGeolocation(string $ipInfo)
    {
        // $key = "619fc25f55adeaacb9f6e04e27f45d26";
        $apiKey = $this->getApiKey();
        $apiUrl = "http://api.ipstack.com/$ipInfo?access_key=$apiKey";

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_URL, $apiUrl);

        $data = curl_exec($ch);

        curl_close($ch);
    
        $ipInfo = json_decode($data, true);

        return $ipInfo;
    }

    /**
     * Confirm validity of ipAddress to be either
     * IPv4 or IPv6.
     *
     * @return string
     */
    public function getVerificationOnIp($ipAddress)
    {
        $ipv4 = filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
        $ipv6 = filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
        if ($ipv4 || $ipv6) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Try to catch the ip-search result host.
     *
     * @return string
     */
    public function getResultHost($ipAddress)
    {
        if ($ipAddress) {
            if (array_key_exists("ip", $ipAddress)) {
                $verified = $this->getVerificationOnIp($ipAddress["ip"]);
            }
            if ($verified) {
                $host = gethostbyaddr($ipAddress["ip"]);
                return $host;
            } else {
                return "Thats not a valid ip address";
            }
        } else {
            return "Thats not a valid ip address";
        }
    }

    /**
     * Try to catch the users ip address
     *
     * @return string
     */
    public function getUserHost()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ipAddress = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ipAddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ipAddress = isset($_SERVER["REMOTE_ADDR"]) ?? $_SERVER['REMOTE_ADDR'];
        }

        return $ipAddress;
    }
}

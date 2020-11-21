<?php

namespace Anax\Geolocator;

/**
 * A model class retrieving data from an external server.
 *
 * @SuppressWarnings(PHPMD.ShortVariable)
 */
class GeolocatorApi
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

    public function ipTrackWithGeolocationAPI(string $ipInfo)
    {
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
     * Confirm validity of ipAddress to be either
     * IPv4 or IPv6.
     *
     * @return string
     */
    public function getVerificationOnBody($body)
    {
        if (is_array($body)) {
            if (array_key_exists("ip", $body)) {
                return true;
            }
            return false;
        }
        return false;
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
            $ipAddress = $_SERVER['REMOTE_ADDR'] ?? isset($_SERVER["REMOTE_ADDR"]);
        }

        return $ipAddress;
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
     * Test routes to test that the API works.
     *
     * @SuppressWarnings(PHPMD)
     *
     * @return string
     */
    public function validateRoutes($postForm)
    {
        if (is_array($postForm)) {
            if (array_key_exists("validateDbwebb", $postForm)) {
                if ($postForm["validateDbwebb"] != null) {
                    return "194.47.150.9";
                }
            }
            if (array_key_exists("validateGoogle", $postForm)) {
                if ($postForm["validateGoogle"] != null) {
                    return "2001:4860:4860::8888";
                }
            }
            if (array_key_exists("validateNone", $postForm)) {
                if ($postForm["validateNone"] != null) {
                    return "";
                }
            }
            if (array_key_exists("validateIp", $postForm)) {
                if ($postForm["validateIp"] != null) {
                    return $postForm["validateIp"];
                }
            }
        }
    }
}

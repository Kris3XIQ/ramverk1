<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class KrisVerifyIpApiController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * This handles POST request
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * @return array
     */
    public function indexActionPost() : array
    {
        // Handle the action and return a response, try/catch incase body is missing.

        try {
            $body = $this->di->get("request")->getBodyAsJson();
            if (is_array($body)) {
                if (array_key_exists("ip", $body)) {
                    $ipAddress = $body["ip"];
                } else {
                    $key = array_key_first($body);
                    $ipAddress = "";
                }
            } else {
                $ipAddress = $body;
            }

            if ($ipAddress === "" || $ipAddress === null) {
                $ipAddress = "";
                $verification = `$key is not a valid key, has to be 'ip'. Look up the instructions on how to properly fetch from the API.`;
            } else {
                $verify4 = filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
                $verify6 = filter_var($ipAddress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
                if ($verify4) {
                    $verification = "ip4";
                } else if ($verify6) {
                    $verification = "ip6";
                } else {
                    $verification = "IP not verified.";
                }
                $host = gethostbyaddr($ipAddress);
            }
        } catch (\Exception $e) {
            $body = "Body is missing, make sure to type in IP in body.";
            $host = "";
            $verification = false;
            $ipAddress = "";
            $host = "IP-address missing.";
        }

        $json = [
            "message" => __METHOD__. ", POST request was successful",
            "verified" => $verification,
            "ip" => $ipAddress,
            "host" => $host
        ];
        return [$json];
    }
}

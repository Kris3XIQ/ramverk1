<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

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
class KrisVerifyIpJsonController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;



    /**
     * @var string $db a sample member variable that gets initialised
     */
    private $db = "not active";



    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize() : void
    {
        // Use to initialise member variables.
        $this->db = "active";
    }



    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return object
     */
    public function indexAction() : object
    {
        // Deal with the action and return a response.
        $title = "IP verification tool(JSON)";

        $page = $this->di->get("page");
        $page->add("ip/ip-validator");

        return $page->render([
            "title" => $title,
        ]);
    }

    /**
     * This handles POST request
     *
     * @SuppressWarnings(PHPMD.UnusedLocalVariable)
     *
     * @return array
     *
     */
    public function indexActionPost() : array
    {
        // Handle the action and return a response, try/catch incase body is missing.
        $validateIp = $_POST["validateIp"] ?? null;
        $validateDbwebb = $_POST["validateDbwebb"] ?? null;
        $validateGoogle = $_POST["validateGoogle"] ?? null;
        $validateNone = $_POST["validateNone"] ?? null;
        if ($validateIp) {
            $ipAddress = $validateIp;
        }
        if ($validateDbwebb) {
            $ipAddress = "194.47.150.9";
            $validateIp = $ipAddress;
            $host = gethostbyaddr($validateIp);
        }
        if ($validateGoogle) {
            $ipAddress = "2001:4860:4860::8888";
            $validateIp = $ipAddress;
            $host = gethostbyaddr($validateIp);
        }
        if ($validateNone) {
            $ipAddress = "91323719.1273123.621316";
            $host = "Host not verified";
        }
        try {
            $verify4 = filter_var($validateIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4);
            $verify6 = filter_var($validateIp, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6);
            if ($verify4) {
                $verification = "IPv4";
                $host = gethostbyaddr($ipAddress);
            } else if ($verify6) {
                $verification = "IPv6";
                $host = gethostbyaddr($ipAddress);
            } else {
                $verification = "Is not verified.";
                $host = "Host not verified";
            }
        } catch (\Exception $e) {
            $body = "Body is missing, make sure to type in IP in body.";
            $verification = false;
            $ipAddress = "";
            $host = "IP-address missing.";
        }

        $json = [
            "message" => __METHOD__. ", POST request was successful",
            "verified" => $verification,
            "ip" => $ipAddress ?? null,
            "host" => $host
        ];
        return [$json];
    }

    /**
     * This is how a general helper method can be created in the controller.
     *
     * @param string $method as the method that handled the controller action
     *
     * @param array $args as an array of arguments.
     *
     * @return string as a message to the output to help understand how the controller method works.
     */
    public function getDetailsOnRequest(
        string $method,
        array $args = []
    ) : string {
        $request        = $this->di->get("request");
        $path           = $request->getRoute();
        $httpMethod     = $request->getMethod();
        $numArgs        = count($args);
        $strArgs        = implode(", ", $args);
        $queryString    = http_build_query($request->getGet(), '', ', ');

        return <<<EOD
            <h1>$method</h1>

            <p>The request was '$path' ($httpMethod).
            <p>Got '$numArgs' arguments: '$strArgs'.
            <p>Query string contains: '$queryString'.
            <p>\$db is '{$this->db}'.
        EOD;
    }

    /**
     * Adding an optional catchAll() method will catch all actions sent to the
     * router. You can then reply with an actual response or return void to
     * allow for the router to move on to next handler.
     * A catchAll() handles the following, if a specific action method is not
     * created:
     * ANY METHOD mountpoint/**
     *
     * @param array $args as a variadic parameter.
     *
     * @return mixed
     *
     * @SuppressWarnings(PHPMD.UnusedFormalParameter)
     */
    public function catchAll(...$args)
    {
        $page = $this->di->get("page");
        $data = [
            "content" => $this->getDetailsOnRequest(__METHOD__, $args),
        ];
        $page->add("anax/v2/article/default", $data);

        return $page->render([
            "title" => __METHOD__,
        ]);
    }
}

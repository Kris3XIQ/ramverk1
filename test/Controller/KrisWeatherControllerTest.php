<?php

namespace Anax\Controller;

use Anax\Response\ResponseUtility;
use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;
use Anax\Service\APIService;
use Anax\Weather\Weather;

/**
 * Test the SampleController.
 */
class KrisWeatherControllerTest extends TestCase
{

    protected $di;

    /**
     * Prepare before each test.
     */
    protected function setUp()
    {
        global $di;

        // Setup di
        $this->di = new DIFactoryConfig();
        $this->di = $di;
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache/anax");

        // Controller setup
        $_SESSION["weatherHistoryJSON"] = null;
        $this->controller = new KrisWeatherController();
        $this->service = $this->di->get("api-service");
        $this->controller->setDI($this->di);
    }

    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $res = $this->controller->indexAction();
        $body = $res->getBody();
        $this->assertStringContainsString("Weather service", $body);
    }

    /**
     * Test the redirect for ActionPost with an empty input.
     */
    public function testRedirectPost()
    {
        $res = $this->controller->indexActionPost();
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    /**
     * Make sure we get the right result back after putting
     * in a correct location as input.
     */
    public function testWeatherCorrectLocationInput()
    {
        $this->di->get("request")->setPost("input", "Stockholm,SE");
        $res = $this->controller->indexActionPost();
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    /**
     * Make sure we get the right result back after putting
     * in a correct IP-address
     */
    public function testWeatherCorrectIpInput()
    {
        $this->di->get("request")->setPost("input", "194.47.150.2");
        $res = $this->controller->indexActionPost();
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    /**
     * Make sure we get the right response back after putting
     * in an incorrect input value.
     */
    public function testWeatherIncorrectLocationInput()
    {
        $this->di->get("request")->setPost("input", "Someplace that doesnt exist");
        $res = $this->controller->indexActionPost();
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    // /**
    //  * Make sure we get the right result from verifying an
    //  * ip6 ip-address.
    //  */
    // public function testIp6Post()
    // {
    //     $this->di->get("request");
    //     $_POST["validateIp"] = "2001:0db8:85a3:0000:0000:8a2e:0370:7334";
    //     $res = $this->controller->indexActionPost();
    //     $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    // }

    // /**
    //  * Make sure that validate dbwebb button-click gives the
    //  * right result.
    //  */
    // public function testValidateDbwebbPost()
    // {
    //     $this->di->get("request");
    //     $_POST["validateDbwebb"] = true;
    //     $res = $this->controller->indexActionPost();
    //     $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    // }

    // /**
    //  * Make sure that validate google button-click gives the
    //  * right result.
    //  */
    // public function testValidateGooglePost()
    // {
    //     $this->di->get("request");
    //     $_POST["validateGoogle"] = true;
    //     $res = $this->controller->indexActionPost();
    //     $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    // }

    // /**
    //  * Make sure we get the right result from not entering an ip.
    //  */
    // public function testValidateNone()
    // {
    //     $this->di->get("request");
    //     $_POST["validateNone"] = true;
    //     $res = $this->controller->indexActionPost();
    //     $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    // }

    // /**
    //  * Test catch-all
    //  */
    // public function testCatchAll()
    // {
    //     $this->controller->initialize();
    //     $res = $this->controller->catchAll();
    //     $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    // }
}

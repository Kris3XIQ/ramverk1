<?php

namespace Kris3XIQ\Controller;

use Anax\Response\ResponseUtility;
use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;
use Kris3XIQ\Service\APIService;
use Kris3XIQ\Weather\Weather;

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
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/test/config/di");

        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        // Set DI
        $this->di = $di;
    }

    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $controller = new KrisWeatherController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $res = $controller->indexAction();
        $body = $res->getBody();
        $this->assertStringContainsString("Weather service", $body);
    }

    /**
     * Test the redirect for ActionPost with an empty input.
     */
    public function testRedirectPost()
    {
        $controller = new KrisWeatherController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $res = $controller->indexActionPost();
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    /**
     * Make sure we get the right result back after putting
     * in a correct location as input.
     */
    public function testWeatherCorrectLocationInput()
    {
        $controller = new KrisWeatherController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $this->di->get("request")->setPost("input", "Stockholm,SE");
        $res = $controller->indexActionPost();
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    /**
     * Make sure we get the right result back after putting
     * in a correct IP-address
     */
    public function testWeatherCorrectIpInput()
    {
        $controller = new KrisWeatherController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $this->di->get("request")->setPost("input", "194.47.150.2");
        $res = $controller->indexActionPost();
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    /**
     * Make sure we get the right response back after putting
     * in an incorrect input value.
     */
    public function testWeatherIncorrectLocationInput()
    {
        $controller = new KrisWeatherController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $this->di->get("request")->setPost("input", "Someplace that doesnt exist");
        $res = $controller->indexActionPost();
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    /**
     * Test weatherHistoryJSON
     */
    public function testWeatherHistoryJSON()
    {
        $controller = new KrisWeatherController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $_SESSION["weatherHistoryJSON"] = [
            "cod" => 200,
            "past_days" => [
                "past_01" => "{}",
                "past_02" => "{}",
                "past_03" => "{}",
                "past_04" => "{}",
                "past_05" => "{}"
            ],
        ];
        
        $res = $controller->indexAction();
        $body = $res->getBody();
        $this->assertStringContainsString("Weather service", $body);

        $_SESSION["weatherHistoryJSON"] = null;
    }
}

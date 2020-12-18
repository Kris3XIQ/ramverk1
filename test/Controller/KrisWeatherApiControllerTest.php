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
class KrisWeatherApiControllerTest extends TestCase
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
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache/anax");

        // Set DI
        $this->di = $di;
    }

    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $controller = new KrisWeatherApiController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $res = $controller->indexAction();
        $body = $res->getBody();
        $this->assertStringContainsString("Weather API service", $body);
    }

    /**
     * Test the redirect for ActionPost with an empty input.
     */
    public function testRedirectPost()
    {
        $controller = new KrisWeatherApiController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $this->di->get("request")->setBody(json_decode(json_encode('{"x":""}')), true);
        $res = $controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * a correct location via a POST request to the API from the
     * website interface
     */
    public function testWeatherApiCorrectLocationInput()
    {
        $controller = new KrisWeatherApiController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $this->di->get("request")->setPost("input", "Stockholm,SE");
        $res = $controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
    * Make sure we get back the correct result when sending
     * a correct ip-address via a POST request to the API from the
     * website interface
     */
    public function testWeatherApiCorrectIpInput()
    {
        $controller = new KrisWeatherApiController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $this->di->get("request")->setPost("input", "194.47.150.2");
        $res = $controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * an incorrect location via a POST request to the API from the
     * website interface
     */
    public function testWeatherApiIncorrectLocationInput()
    {
        $controller = new KrisWeatherApiController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $this->di->get("request")->setPost("input", "Does not exist");
        $res = $controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * an incorrect ip-address via a POST request to the API from the
     * website interface
     */
    public function testWeatherApiIncorrectIpInput()
    {
        $controller = new KrisWeatherApiController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $this->di->get("request")->setPost("input", "412413123");
        $res = $controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * an empty input via a POST request to the API from the
     * website interface
     */
    public function testWeatherApiInorrectInput()
    {
        $controller = new KrisWeatherApiController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $this->di->get("request")->setPost("input", "");
        $this->di->get("request")->setBody(json_decode(json_encode('{"input":""}')), true);
        $res = $controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * an invalid request via a third party application, such as
     * ARC or POSTMAN
     */
    public function testWeatherApiInorrectInputThirdParty()
    {
        $controller = new KrisWeatherApiController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $this->di->get("request")->setPost("input", null);
        $this->di->get("request")->setBody(json_decode(json_encode('{"input":"194.47.150.2"}')), true);
        $res = $controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * an invalid request via a third party application, such as
     * ARC or POSTMAN
     */
    public function testWeatherApiInorrectJSONThirdParty()
    {
        $controller = new KrisWeatherApiController();
        $this->di->get("api-service");
        $controller->setDI($this->di);

        $this->di->get("request")->setPost("input", false);
        $this->di->get("request")->setBody(json_decode(json_encode('{"input:"194.47.150.2"}')), true);
        $res = $controller->indexActionPost();
        $this->assertIsArray($res);
    }
}

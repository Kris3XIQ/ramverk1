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
        $this->di = new DIFactoryConfig();
        $this->di = $di;
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache/anax");

        // Controller setup
        $this->controller = new KrisWeatherApiController();
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
        $this->assertStringContainsString("Weather API service", $body);
    }

    /**
     * Test the redirect for ActionPost with an empty input.
     */
    public function testRedirectPost()
    {
        $this->di->get("request");
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * a correct location via a POST request to the API from the
     * website interface
     */
    public function testWeatherApiCorrectLocationInput()
    {
        $this->di->get("request")->setPost("input", "Stockholm,SE");
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
    * Make sure we get back the correct result when sending
     * a correct ip-address via a POST request to the API from the
     * website interface
     */
    public function testWeatherApiCorrectIpInput()
    {
        $this->di->get("request")->setPost("input", "194.47.150.2");
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * an incorrect location via a POST request to the API from the
     * website interface
     */
    public function testWeatherApiIncorrectLocationInput()
    {
        $this->di->get("request")->setPost("input", "Does not exist");
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * an incorrect ip-address via a POST request to the API from the
     * website interface
     */
    public function testWeatherApiIncorrectIpInput()
    {
        $this->di->get("request")->setPost("input", "412413123");
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * an empty input via a POST request to the API from the
     * website interface
     */
    public function testWeatherApiInorrectInput()
    {
        $this->di->get("request")->setPost("input", "");
        $this->di->get("request")->setBody(json_decode(json_encode('{"input":""}')), true);
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * an invalid request via a third party application, such as
     * ARC or POSTMAN
     */
    public function testWeatherApiInorrectInputThirdParty()
    {
        $this->di->get("request")->setPost("input", null);
        $this->di->get("request")->setBody(json_decode(json_encode('{"input":"194.47.150.2"}')), true);
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * an invalid request via a third party application, such as
     * ARC or POSTMAN
     */
    public function testWeatherApiInorrectJSONThirdParty()
    {
        $this->di->get("request")->setPost("input", false);
        $this->di->get("request")->setBody(json_decode(json_encode('{"input:"194.47.150.2"}')), true);
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }
}

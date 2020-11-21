<?php

namespace Anax\Controller;

use Anax\Response\ResponseUtility;
use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleController.
 */
class KrisIpGeoLocatorApiControllerTest extends TestCase
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
        $this->controller = new KrisIpGeoLocatorApiController();
        $this->controller->setDI($this->di);
        $this->di->get("request")->setPost("validateIp", null);
        $this->di->get("request")->setPost("validateDbwebb", null);
        $this->di->get("request")->setPost("validateGoogle", null);
        $this->di->get("request")->setPost("validateNone", null);
    }

    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $res = $this->controller->indexAction();
        $body = $res->getBody();
        $this->assertStringContainsString("IP Validator with geolocation API", $body);
    }

    /**
     * Test JSON response on correct POST method.
     */
    public function testCorrectPostWebsite()
    {
        $this->di->get("request")->setPost("validateIp", "2001:4860:4860::8888");
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }
    
    /**
     * Test JSON response when fetching from the API using a third
     * party software, such as ARC or Postman.
     */
    public function testCorrectPostThirdParty()
    {
        $this->di->get("request")->setBody(json_decode(json_encode('{"ip":"194.47.159.9"}')), true);
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Test JSON response when fetching from the API using a third
     * party software, such as ARC or Postman without a 'key' value.
     */
    public function testCorrectPostThirdPartyNoKey()
    {
        $this->di->get("request")->setBody(json_encode("194.47.159.9"));
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Test JSON response when fetching from the API using a third
     * party software, such as ARC or Postman with an invalid key - any key
     * not being 'ip' will get rejected.
     */
    public function testFailedPostThirdPartyWithKey()
    {
        $this->di->get("request")->setBody(json_decode(json_encode('{"invalid":"194.47.159.9"}')), true);
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Testroute for clicking on ValidateDbwebb button.
     */
    public function testTestRouteDbwebbButton()
    {
        $this->di->get("request")->setPost("validateDbwebb", true);
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Testroute for clicking on ValidateGoogle button.
     */
    public function testTestRouteGoogleButton()
    {
        $this->di->get("request")->setPost("validateGoogle", true);
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Testroute for clicking on ValidateNone button.
     */
    public function testTestRouteNoneButton()
    {
        $this->di->get("request")->setPost("validateNone", true);
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }
}

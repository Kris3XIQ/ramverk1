<?php

namespace Anax\Controller;

use Anax\Response\ResponseUtility;
use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleController.
 */
class KrisIpGeoLocatorControllerTest extends TestCase
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
        $this->controller = new KrisIpGeoLocatorController();
        $this->controller->setDI($this->di);
    }

    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $res = $this->controller->indexAction();
        $body = $res->getBody();
        $this->assertStringContainsString("IP Validator with geolocation", $body);
    }

    /**
     * Test the module function to get Result Host from posting an
     * invalid IP-address.
     */
    public function testInvalidIpPost()
    {
        $session = $this->di->get("session");
        $this->di->get("request")->setPost("validateIp", "InvalidIP");
        $this->controller->indexActionPost();
        $session->get("ipInfo");
        $res = $this->controller->indexAction();
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    /**
     * Test the module function to get Result Host from posting an
     * IPv4 ip-address.
     */
    public function testGetResultHostIPv4()
    {
        $session = $this->di->get("session");
        $this->di->get("request")->setPost("validateIp", "194.47.150.2");
        $this->controller->indexActionPost();
        $session->get("ipInfo");
        $res = $this->controller->indexAction();
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    /**
     * Test the module function to get Result Host from posting an
     * IPv6 ip-address.
     */
    public function testGetResultHostIPv6()
    {
        $session = $this->di->get("session");
        $this->di->get("request")->setPost("validateIp", "2001:4860:4860::8888");
        $this->controller->indexActionPost();
        $session->get("ipInfo");
        $res = $this->controller->indexAction();
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    /**
     * Test posting an IP-address
     */
    public function testCorrectPost()
    {
        $this->di->get("request")->setPost("validateIp", "2001:4860:4860::8888");
        $res = $this->controller->indexActionPost();
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }
}

<?php

namespace Anax\Controller;

use Anax\Response\ResponseUtility;
use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleController.
 */
class KrisVerifyIpJsonControllerTest extends TestCase
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
        $this->controller = new KrisVerifyIpJsonController();
        $this->controller->setDI($this->di);
    }

    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $res = $this->controller->indexAction();
        $body = $res->getBody();
        $this->assertStringContainsString("IP verification tool(JSON)", $body);
    }

    /**
     * Make sure we get a JSON-formatted result.
     */
    public function testJsonResult()
    {
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get the right result from verifying an
     * ip4 ip-address.
     */
    public function testJsonIp4Post()
    {
        $this->di->get("request");
        $_POST["validateIp"] = "194.47.150.9";
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get the right result from verifying an
     * ip6 ip-address.
     */
    public function testJsonIp6Post()
    {
        $this->di->get("request");
        $_POST["validateIp"] = "2001:0db8:85a3:0000:0000:8a2e:0370:7334";
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure that validate dbwebb button-click gives the
     * right result.
     */
    public function testJsonValidateDbwebbPost()
    {
        $this->di->get("request");
        $_POST["validateDbwebb"] = true;
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure that validate google button-click gives the
     * right result.
     */
    public function testJsonValidateGooglePost()
    {
        $this->di->get("request");
        $_POST["validateGoogle"] = true;
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get the right result from not entering an ip.
     */
    public function testJsonValidateNone()
    {
        $this->di->get("request");
        $_POST["validateNone"] = true;
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Test catch-all
     */
    public function testCatchAll()
    {
        $controller = new KrisVerifyIpJsonController();
        $controller->setDI($this->di);
        $controller->initialize();
        $res = $controller->catchAll();
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }
}

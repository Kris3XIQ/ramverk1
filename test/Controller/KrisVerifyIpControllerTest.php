<?php

namespace Anax\Controller;

use Anax\Response\ResponseUtility;
use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleController.
 */
class KrisVerifyIpControllerTest extends TestCase
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
        $di = $this->di;
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache/anax");

        // Controller setup
        $this->controller = new KrisVerifyIpController();
        $this->controller->setDI($this->di);
        $this->di->set("request", "\Anax\Request\Request");
    }

    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $res = $this->controller->indexAction();
        $body = $res->getBody();
        $this->assertStringContainsString("IP verification tool", $body);
    }

    /**
     * Test the redirect for ActionPost.
     */
    public function testRedirectPost()
    {
        $controller = new KrisVerifyIpController();
        $controller->setDI($this->di);

        $res = $controller->indexActionPost();
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    /**
     * Test route for posting an IP address.
     */
    public function testPostActionWithIpAdress()
    {
        $request = $this->di->get("request");
        $request->setPost("validateIp", "194.47.150.9");

        $res = $this->controller->indexActionPost();

        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    /**
     * Test route for clicking on Google link.
     */
    public function testPostActionClickGoogle()
    {
        $request = $this->di->get("request");
        $request->setPost("validateGoogle", true);
        $res = $this->controller->indexActionPost();

        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }

    /**
     * Test catch-all
     */
    public function testCatchAll()
    {
        $controller = new KrisVerifyIpController();
        $controller->setDI($this->di);
        $controller->initialize();
        $res = $controller->catchAll();
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
    }
}

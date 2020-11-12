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
        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache/anax");

        $this->di = $di;
    }

    /**
     * Test the route "index".
     */
    public function testIndexAction()
    {
        $controller = new KrisVerifyIpJsonController();
        $controller->setDI($this->di);

        $res = $controller->indexAction();
        $body = $res->getBody();
        $this->assertStringContainsString("IP verification tool(JSON)", $body);
    }

    /**
     * Test the redirect for ActionPost.
     */
    public function testJsonResult()
    {
        $controller = new KrisVerifyIpJsonController();
        $controller->setDI($this->di);

        $res = $controller->indexActionPost();
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

<?php
namespace Anax\Controller;

use Anax\Response\ResponseUtility;
use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;

/**
 * Test the SampleController.
 */
class KrisVerifyIpApiControllerTest extends TestCase
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
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        
        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache/anax");
        
        $this->di = $di;
        // Controller setup
        $this->controller = new KrisVerifyIpApiController();
        $this->controller->setDI($this->di);
    }

    /**
     * Make sure we get back result in JSON format.
     */
    public function testJsonResult()
    {
        $this->di->get("request")->setPost("ip", "192.0.0.0");
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }
}

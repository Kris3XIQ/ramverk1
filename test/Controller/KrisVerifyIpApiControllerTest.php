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
        $di = $this->di;
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        
        // Use a different cache dir for unit test
        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache/anax");
        
        // Controller setup
        $this->controller = new KrisVerifyIpApiController();
        $this->controller->setDI($this->di);
    }

    /**
     * Make sure we get back result in JSON format, even without
     * sending a body to the API.
     */
    public function testJsonResult()
    {
        $this->di->get("request");
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * an IPv4 address.
     */
    public function testApiIp4PostJson()
    {
        $this->di->get("request")->setBody(json_decode(json_encode('{"ip":"194.47.159.9"}')), true);
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when sending
     * an IPv6 address.
     */
    public function testApiIp6PostJson()
    {
        $this->di->get("request")->setBody(json_decode(json_encode('{"ip":"2001:4860:4860::8888"}')), true);
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when not sending
     * a valid ip.
     */
    public function testApiNoValidKey()
    {
        $this->di->get("request")->setBody(json_decode(json_encode('{"test":"232323232"}')), true);
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }

    /**
     * Make sure we get back the correct result when not sending
     * a valid ip.
     */
    public function testApiNoValidIp()
    {
        $this->di->get("request")->setBody(json_decode(json_encode('{"ip":"232323232"}')), true);
        $res = $this->controller->indexActionPost();
        $this->assertIsArray($res);
    }
}

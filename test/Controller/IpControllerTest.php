<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;
use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

/**
 * Test the SampleController.
 */
class IpControllerTest extends TestCase
{
    public function testIndexAction()
    {
        $controller = new IpController();
        $controller->initialize();
        $res = $controller->indexAction();
        $this->assertContains("db is active", $res);
    }

    public function testJsonActionGet()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new IpController();
        $controller->setDI($di);
        $controller->initialize();

        $res = $controller->jsonActionGet();
        $this->assertIsArray($res);
        $this->assertArrayHasKey("message", $res[0]);
        $this->assertContains("di contains", $res[0]["message"]);
        $this->assertArrayHasKey("di", $res[0]);
        $this->assertContains("configuration", $res[0]["di"]);
        $this->assertContains("request", $res[0]["di"]);
        $this->assertContains("response", $res[0]["di"]);
    }

    public function testPageActionGet()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new IpController();
        $controller->setDI($di);
        $controller->initialize();

        $res = $controller->pageActionGet();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
        
        $body = $res->getBody();
        $this->assertContains("<h3>IP-adress validator</h3>", $body);
    }

    public function testCheckIP4ActionPost()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new IpController();
        $controller->setDI($di);
        $controller->initialize();

        $di->get("request")->setPost("ipadress", "127.0.0.1");
        $res = $controller->checkIPActionPost();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
        
        $body = $res->getBody();
        $this->assertContains("linux.dbwebb.se", $body);
    }

    public function testCheckIP6ActionPost()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new IpController();
        $controller->setDI($di);
        $controller->initialize();

        $di->get("request")->setPost("ipadress", "2a03:2880:f003:c07:face:b00c::2");
        $res = $controller->checkIPActionPost();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
        
        $body = $res->getBody();
        $this->assertContains("facebook.com", $body);
    }

    public function testFalseCheckIPActionPost()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new IpController();
        $controller->setDI($di);
        $controller->initialize();

        $di->get("request")->setPost("ipadress", "xxxxxxx");
        $res = $controller->checkIPActionPost();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
        
        $body = $res->getBody();
        $this->assertContains("är inte en giltig IP adress", $body);
    }

    public function testCheckIP4JSONActionGet()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new IpController();
        $controller->setDI($di);
        $controller->initialize();

        $di->get("request")->setGet("ipadressJSON", "127.0.0.1");
        $res = $controller->checkIPJSONActionGet();
        $this->assertIsArray($res);
        
        $this->assertContains("linux.dbwebb.se", $res[0]["hostJSON"]);
    }

    public function testCheckIP6JSONActionGet()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new IpController();
        $controller->setDI($di);
        $controller->initialize();

        $di->get("request")->setGet("ipadressJSON", "2a03:2880:f003:c07:face:b00c::2");
        $res = $controller->checkIPJSONActionGet();
        $this->assertIsArray($res);
        
        $this->assertContains("facebook.com", $res[0]["hostJSON"]);
    }

    public function testFalseCheckIPJSONActionGet()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new IpController();
        $controller->setDI($di);
        $controller->initialize();

        $di->get("request")->setGet("ipadressJSON", "xxxxxxx");
        $res = $controller->checkIPJSONActionGet();
        $this->assertIsArray($res);
        
        $this->assertContains("är inte en giltig IP adress", $res[0]["ipRes"]);
    }
}

<?php

namespace Anax\Controller;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;
use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class WeatherControllerTest extends TestCase
{
    public function testIndexAction()
    {
        $controller = new WeatherController();
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

        $controller = new WeatherController();
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

        $controller = new WeatherController();
        $controller->setDI($di);
        $controller->initialize();

        $res = $controller->pageActionGet();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
        
        $body = $res->getBody();
        $this->assertContains("<h3>Väderprognos för specifik plats</h3>", $body);
    }

    public function testCheckAdressActionPost()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new WeatherController();
        $controller->setDI($di);
        $controller->initialize();

        $di->get("request")->setPost("ipadress", "213.52.129.125");
        $res = $controller->checkAdressActionPost();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
        
        $body = $res->getBody();
        $this->assertContains("Chapel Allerton", $body);
    }

    public function testLongLatCheckAdressActionPost()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new WeatherController();
        $controller->setDI($di);
        $controller->initialize();

        $di->get("request")->setPost("inputLong", "17.14174");
        $di->get("request")->setPost("inputLat", "60.67452");
        $res = $controller->checkAdressActionPost();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
        
        $body = $res->getBody();
        $this->assertContains("longitud: 17.14174 och latitud: 60.67452", $body);
    }

    public function testLongCheckAdressActionPost()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new WeatherController();
        $controller->setDI($di);
        $controller->initialize();

        $di->get("request")->setPost("inputLong", "17.14174");
        $res = $controller->checkAdressActionPost();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
        
        $body = $res->getBody();
        $this->assertContains("Du måste ange antingen en ip-adress eller longitud och latitud!", $body);
    }

    public function testFalseLongLatCheckAdressActionPost()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new WeatherController();
        $controller->setDI($di);
        $controller->initialize();

        $di->get("request")->setPost("inputLong", "xxxxx");
        $di->get("request")->setPost("inputLat", "xxxxx");
        $res = $controller->checkAdressActionPost();
        
        $body = $res->getBody();
        $this->assertContains("Du har angett felaktiga koordinater!", $body);
    }

    public function testFalseCheckAdressActionPost()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new WeatherController();
        $controller->setDI($di);
        $controller->initialize();

        $di->get("request")->setPost("ipadress", "xxxxxxx");
        $res = $controller->checkAdressActionPost();
        $this->assertIsObject($res);
        $this->assertInstanceOf("Anax\Response\Response", $res);
        $this->assertInstanceOf("Anax\Response\ResponseUtility", $res);
        
        $body = $res->getBody();
        $this->assertContains("Du har angett en ogiltig ip-adress", $body);
    }

    public function testFalseCheckIPJSONActionGet()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new WeatherController();
        $controller->setDI($di);
        $controller->initialize();

        $di->get("request")->setGet("ipadressJSON", "xxxxxxx");
        $res = $controller->checkIPJSONActionGet();
        $this->assertIsArray($res);
        
        $this->assertContains("Du har angett en ogiltig ip-adress!", $res[0]["error"]);
    }

    public function testFalseLongLatCheckIPJSONActionGet()
    {
        global $di;

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");

        $di->get("cache")->setPath(ANAX_INSTALL_PATH . "/test/cache");

        $controller = new WeatherController();
        $controller->setDI($di);
        $controller->initialize();

        $di->get("request")->setGet("inputLongJSON", "xxxxx");
        $di->get("request")->setGet("inputLatJSON", "xxxxx");
        $res = $controller->checkIPJSONActionGet();
        $this->assertIsArray($res);
        
        $this->assertContains("Du har angett felaktiga koordinater!", $res[0]["error"]);
    }
}

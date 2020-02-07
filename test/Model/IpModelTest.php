<?php

namespace Anax\models;

use Anax\DI\DIFactoryConfig;
use PHPUnit\Framework\TestCase;
use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class IpModelTest extends TestCase
{
    public function testGetUserIp()
    {
        $model = new IpAdress();

        $di = new DIFactoryConfig();
        $di->loadServices(ANAX_INSTALL_PATH . "/config/di");
        $request = $di->get("request");
        $request->setServer("HTTP_CLIENT_IP", "83.249.109.85");

        $res = $model->getUserIp($request);
        $this->assertEquals("83.249.109.85", $res);
    }

    public function testJsonActionGet()
    {
        $ipAdress = "2a03:2880:f003:c07:face:b00c::2";
        $model = new IpAdress($ipAdress);

        $res = $model->validateIp();
        $this->assertContains("en giltig IPv6 adress", $res[0]);
    }

    public function testGetLocation()
    {
        $model = new IpAdress();
        $model->ipAdress = "2a03:2880:f003:c07:face:b00c::2";

        $res = $model->getLocation();
        $this->assertContains("Dublin", $res['city']);
    }
}

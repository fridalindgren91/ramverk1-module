<?php
return [
    "services" => [
        "ipstack" => [
            "shared" => true,
            "callback" => function () {
                $getConfig = $this->get("configuration");
                $config = $getConfig->load("weather_api_keys.php");
                $apiKey = $config["config"]["ipstack"];
                $ipstack = new \Anax\DI\Ipstack($apiKey);
                $ipstack->setDi($this);
                return $ipstack;
            },
        ],
    ],
];

<?php
return [
    "services" => [
        "curl" => [
            "shared" => true,
            "callback" => function () {
                $getConfig = $this->get("configuration");
                $curl = new \Anax\DI\Curl();
                $curl->setDi($this);
                return $curl;
            },
        ],
    ],
];

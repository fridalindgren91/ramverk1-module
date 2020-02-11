<?php

return [
    "routes" => [
        [
            "info" => "Väderprognoser för specifika områden.",
            "mount" => "weather",
            "handler" => "\Anax\Controller\WeatherController",
        ],
    ]
];

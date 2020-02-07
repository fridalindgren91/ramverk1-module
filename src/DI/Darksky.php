<?php
namespace Anax\DI;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class Darksky implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getWeatherData($longitude, $latitude)
    {
        $url = 'https://api.darksky.net/forecast';
        $question = [$this->apiKey . '/' . $latitude . ',' . $longitude . '?lang=sv&units=auto'];
        
        $curl = $this->di->get("curl");

        $weatherJSON = $curl->multiCurl($url, $question);

        return $weatherJSON;
    }
}

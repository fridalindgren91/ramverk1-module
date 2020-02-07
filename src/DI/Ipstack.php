<?php
namespace Anax\DI;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class Ipstack implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function getIpInfo($ipadress)
    {
        if (isset($ipadress)) {
            $location = 'http://api.ipstack.com/' . $ipadress . '?access_key=' . $this->apiKey;
            $locationJSON = file_get_contents($location);
            $locationJSONObject = json_decode($locationJSON, true);
            return $locationJSONObject;
        }
    }
}

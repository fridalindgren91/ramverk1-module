<?php
namespace Anax\models;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class IpAdress
{
    use ContainerInjectableTrait;

    public $apiKey = "613d9afc10fd24328b5dfbf2528604a7";
    public $ipAdress;

    public function __construct($ipAdress = null)
    {
        $this->ipAdress = $ipAdress;
    }

    public function getUserIp($request)
    {
        if (!empty($request->getServer('HTTP_CLIENT_IP'))) {
            $this->ipAdress = $request->getServer('HTTP_CLIENT_IP');
        } elseif (!empty($request->getServer('HTTP_X_FORWARDED_FOR'))) {
            $this->ipAdress = $request->getServer('HTTP_X_FORWARDED_FOR');
        } else {
            $this->ipAdress = $request->getServer('REMOTE_ADDR');
        }
        return $this->ipAdress;
    }

    public function validateIp()
    {
        if (filter_var($this->ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV6)) {
            $ipRes = $this->ipAdress . " är en giltig IPv6 adress!";
            $host = gethostbyaddr($this->ipAdress);
        } elseif (filter_var($this->ipAdress, FILTER_VALIDATE_IP, FILTER_FLAG_IPV4)) {
            $ipRes = $this->ipAdress . " är en giltig IPv4 adress!";
            $host = gethostbyaddr($this->ipAdress);
        } else {
            $ipRes = $this->ipAdress . " är inte en giltig IP adress";
            $host = null;
        }

        $resArray = array($ipRes, $host);
        return $resArray;
    }

    public function getLocation()
    {
        $location = 'http://api.ipstack.com/' . $this->ipAdress . '?access_key=' . $this->apiKey;
        $locationJSON = file_get_contents($location);
        $locationJSONObject = json_decode($locationJSON, true);

        return $locationJSONObject;
    }
}

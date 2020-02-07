<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

use Anax\DI;

use Anax\models\ipAdress;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class WeatherController implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    /**
     * @var string $db a sample member variable that gets initialised
     */
    private $db = "not active";

    /**
     * The initialize method is optional and will always be called before the
     * target method/action. This is a convienient method where you could
     * setup internal properties that are commonly used by several methods.
     *
     * @return void
     */
    public function initialize() : void
    {
        // Use to initialise member variables.
        $this->db = "active";
    }

    /**
     * This is the index method action, it handles:
     * ANY METHOD mountpoint
     * ANY METHOD mountpoint/
     * ANY METHOD mountpoint/index
     *
     * @return string
     */
    public function indexAction() : string
    {
        return __METHOD__ . ", \$db is {$this->db}";
    }

    public function jsonActionGet() : array
    {
        $services = implode(", ", $this->di->getServices());
        $json = [
            "message" => __METHOD__ . "<p>\$di contains:
            $services",
            "di" => $this->di->getServices(),
        ];
        return [$json];
    }

    public function pageActionGet() : object
    {
        $page = $this->di->get("page");
        
        $ipModel = new IpAdress();
        $userIp = $ipModel->getUserIp($this->di->get("request"));

        $data = [
            "content" => "<h3>Väderprognos för specifik plats</h3>",
            "contentJSON" => "<h3>Väderprognos för specifik plats (JSON)",
            "userIp" => $userIp
        ];

        $title = "Väderprognos";

        $page->add("weather/weather", $data);
        return $page->render([
            "title" => $title
        ]);
    }

    public function checkAdressActionPost() : object
    {
        $ipAdress = null;
        $data = [];
        $errorMessage = null;
        $longitude = null;
        $latitude = null;
        $weatherJSONObject = null;
        $inputLong = null;
        $inputLat = null;
        $city = null;

        if ($this->di->get("request")->getPost("ipadress") != null) {
            $ipAdress = $this->di->get("request")->getPost("ipadress");
        }
        if ($this->di->get("request")->getPost("inputLong") != null && $this->di->get("request")->getPost("inputLat") != null) {
            $inputLong = $this->di->get("request")->getPost("inputLong");
            $inputLat = $this->di->get("request")->getPost("inputLat");
        }
        if (($ipAdress == null) && $inputLat == null && $inputLong == null) {
            $errorMessage = "Du måste ange antingen en ip-adress eller longitud och latitud!";
        }

        if ($ipAdress != null) {
            $validateIp = $this->validateIp($ipAdress);
            if ($validateIp == false) {
                $errorMessage = "Du har angett en ogiltig ip-adress!";
            } else {
                $ipstack = $this->di->get("ipstack");
                $longlatJSONObject = $ipstack->getIpInfo($ipAdress);
                $city = $longlatJSONObject["city"];
                $longitude = $longlatJSONObject["longitude"];
                $latitude = $longlatJSONObject["latitude"];
                $darksky = $this->di->get("darksky");
                $weatherJSONObject = $darksky->getWeatherData($longitude, $latitude);
            }
        } elseif ($ipAdress == null && $inputLong != null && $inputLat != null) {
            $latitude = $inputLat;
            $longitude = $inputLong;
            $darksky = $this->di->get("darksky");
            $weatherJSONObject = $darksky->getWeatherData($longitude, $latitude);
            if (isset($weatherJSONObject[0]["code"]) && ($weatherJSONObject[0]["code"] == "400")) {
                $errorMessage = "Du har angett felaktiga koordinater!";
                $weatherJSONObject = null;
            }
        }
        
        $data = [
            "content" => "<h3>Väderprognos</h3>",
            "contentJSON" => "<h3>Väderprognos (JSON)",
            "result" => $weatherJSONObject,
            "longitude" => $longitude,
            "latitude" => $latitude,
            "userIp" => $ipAdress,
            "errorMessage" => $errorMessage,
            "city" => $city
        ];

        $page = $this->di->get("page");
        $title = "Väderprognos";
        $page->add("weather/weather", $data);
        return $page->render([
            "title" => $title
        ]);
    }

    public function checkIPJSONActionGet() : array
    {
        $ipAdressJSON = null;
        $inputLongJSON = null;
        $inputLatJSON = null;
        $weatherJSONObject = null;
        $errorMessage = null;
        $city = null;

        if ($this->di->get("request")->getGet("ipadressJSON") != null) {
            $ipAdressJSON = $this->di->get("request")->getGet("ipadressJSON");
        }
        if ($this->di->get("request")->getGet("inputLongJSON") != null && $this->di->get("request")->getGet("inputLatJSON") != null) {
            $inputLongJSON = $this->di->get("request")->getGet("inputLongJSON");
            $inputLatJSON = $this->di->get("request")->getGet("inputLatJSON");
        }
        if (($ipAdressJSON == null) && $inputLatJSON == null && $inputLongJSON == null) {
            $errorMessage = "Du måste ange antingen en ip-adress eller longitud och latitud!";
        }

        if ($ipAdressJSON != null) {
            $validateIp = $this->validateIp($ipAdressJSON);
            if ($validateIp == false) {
                $errorMessage = "Du har angett en ogiltig ip-adress!";
            } else {
                $ipstack = $this->di->get("ipstack");
                $longlatJSONObject = $ipstack->getIpInfo($ipAdressJSON);
                $longitude = $longlatJSONObject["longitude"];
                $latitude = $longlatJSONObject["latitude"];
                $city = $longlatJSONObject["city"];
                $darksky = $this->di->get("darksky");
                $weatherJSONObject = $darksky->getWeatherData($longitude, $latitude);
            }
        } elseif ($ipAdressJSON == null && $inputLongJSON != null && $inputLatJSON != null) {
            $latitude = $inputLatJSON;
            $longitude = $inputLongJSON;
            $darksky = $this->di->get("darksky");
            $weatherJSONObject = $darksky->getWeatherData($longitude, $latitude);
            if (isset($weatherJSONObject[0]["code"]) && ($weatherJSONObject[0]["code"] == "400")) {
                $errorMessage = "Du har angett felaktiga koordinater!";
                $weatherJSONObject = null;
            }
        }

        if ($errorMessage != null) {
            $json = [
                "error" => $errorMessage
            ];
            return [$json];
        } elseif ($city != null) {
            $json = [
                "sammanfattning" => $weatherJSONObject[0]["daily"]["summary"],
                "plats" => $city,
                "väderObjekt" => $weatherJSONObject[0]["daily"]["data"]
            ];
            return [$json];
        } else {
            $json = [
                "sammanfattning" => $weatherJSONObject[0]["daily"]["summary"],
                "longitud" => $longitude,
                "latitude" => $latitude,
                "väderObjekt" => $weatherJSONObject[0]["daily"]["data"]
            ];
            return [$json];
        }
    }

    public function validateIp($ip)
    {
        if (filter_var($ip, FILTER_VALIDATE_IP)) {
            return true;
        } else {
            return false;
        }
    }
}

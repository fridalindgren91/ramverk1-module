<?php

namespace Anax\Controller;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

use Anax\models\ipAdress;

// use Anax\Route\Exception\ForbiddenException;
// use Anax\Route\Exception\NotFoundException;
// use Anax\Route\Exception\InternalErrorException;

/**
 * A sample controller to show how a controller class can be implemented.
 * The controller will be injected with $di if implementing the interface
 * ContainerInjectableInterface, like this sample class does.
 * The controller is mounted on a particular route and can then handle all
 * requests for that mount point.
 *
 * @SuppressWarnings(PHPMD.TooManyPublicMethods)
 */
class IpController implements ContainerInjectableInterface
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
        // Deal with the action and return a response.
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

        $ipModel = new ipAdress();
        // $userIp = $ipModel->getUserIp($this->di->get("request"));

        $data = [
            "content" => "<h3>IP-adress validator</h3>",
            "contentJSON" => "<h3>IP-adress validator (JSON)",
        ];

        $title = "IP validator";

        $page->add("ip/ip", $data);
        return $page->render([
            "title" => $title
        ]);
    }

    public function checkIPActionPost() : object
    {
        $ipAdress = $this->di->get("request")->getPost("ipadress");
        $ipModel = new ipAdress($ipAdress);
        $valRes = $ipModel->validateIp();

        $page = $this->di->get("page");
        $data = [
            "content" => "<h3>IP-adress validator</h3>",
            "contentJSON" => "<h3>IP-adress validator (JSON)",
            "result" => "<p>" . $valRes[0] . "</p>",
            "host" => $valRes[1]
        ];

        $title = "IP validator";

        $page->add("ip/ip", $data);
        return $page->render([
            "title" => $title
        ]);
    }

    public function checkIPJSONActionGet() : array
    {
        $ipJSON = $this->di->get("request")->getGet("ipadressJSON");
        $ipModel = new ipAdress($ipJSON);
        $valRes = $ipModel->validateIp();

        $json = [
            "ipAdress" => $ipJSON,
            "ipRes" => $valRes[0],
            "hostJSON" => $valRes[1]
        ];
        return [$json];
    }
}

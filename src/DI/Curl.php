<?php
namespace Anax\DI;

use Anax\Commons\ContainerInjectableInterface;
use Anax\Commons\ContainerInjectableTrait;

class Curl implements ContainerInjectableInterface
{
    use ContainerInjectableTrait;

    public function multiCurl($url, $questionArray)
    {
        $opt = [
            CURLOPT_RETURNTRANSFER => true,
        ];

        $mh = curl_multi_init();
        $chAll = [];
        
        foreach ($questionArray as $q) {
            $ch = curl_init("$url/$q");
            curl_setopt_array($ch, $opt);
            curl_multi_add_handle($mh, $ch);
            $chAll[] = $ch;
        }

        $running = null;

        do {
            curl_multi_exec($mh, $running);
        } while ($running);

        foreach ($chAll as $ch) {
            curl_multi_remove_handle($mh, $ch);
        }
        curl_multi_close($mh);

        $response = [];
        foreach ($chAll as $ch) {
            $data = curl_multi_getcontent($ch);
            $response[] = json_decode($data, true);
        }

        return $response;
    }
}

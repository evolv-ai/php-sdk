<?php
declare(strict_types=1);

namespace App\EvolvBeacon;

require 'vendor/autoload.php';
require_once __DIR__ . '/EvolvStore.php';

ini_set('display_errors', 'on');

class Beacon
{

    public $endpoint;
    public $environment;
    public $data;
    private $limit = 50;

    public function emit($environment, $endpoint, $data, $flesh_data , $flash)
    {

        if ($flash == true) {

            $this->flush($environment, $endpoint, $data);

        }
        else {

            if(count($flesh_data) >= $this->limit){

                foreach ($flesh_data as $key => $value){

                    $this->flush($environment, $endpoint, $value);

                }
            }

        }

    }

    public function flush($environment, $endpoint, $data)
    {
        $url = $endpoint . '/' . $environment . '/events';

        echo $url . "<br>";

        $data = [
            'type' => $data['type'],
            'uid' => $data['uid'],
            'metadata' => $data['metadata'],
        ];

        echo "<pre>";
        print_r($data);
        echo "</pre>";

        $data = http_build_query($data);

        $ch = curl_init();

        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data,
            CURLOPT_HEADER => true,
            CURLOPT_RETURNTRANSFER => true
        ]);

        $result = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);

        $header = substr($result, 0, $header_size);

        $info = curl_getinfo($ch);

        curl_close($ch);

        //echo "<pre>";
          // print_r($result);
        //  print_r($info);
       // echo "</pre>";
    }

}
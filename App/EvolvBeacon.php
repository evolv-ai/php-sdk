<?php
declare(strict_types=1);

namespace App\EvolvBeacon;

require 'vendor/autoload.php';
require_once __DIR__ . '/EvolvStore.php';

ini_set('display_errors', 'on');

class Beacon{

    public $endpoint;
    public $environment;

    public function emit($environment, $uid, $endpoint, $data){
        $url = $endpoint . '/' . $environment .'/events';

        $data = [
            'type' => $data['type'],
            'uid' => $data['uid'],
            'metadata' => $data['metadata'],
        ];

        $data = http_build_query($data);

        $ch = curl_init();

        curl_setopt_array($ch,[
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

        echo "<pre>";
      //  print_r($result);
      //  print_r($info);
        echo "</pre>";
    }



}
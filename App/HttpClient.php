<?php

declare(strict_types=1);

namespace Evolv;

class HttpClient {
    private const USER_AGENT = 'Evolv-PHP-SDK/1.0';

    public function get(string $url): string
    {
        $opts = array(
            'https' => array(
                'method' => "GET",
                'header' => "Content-type: application/json \r\n" .
                           "User-Agent: " . self::USER_AGENT . " \r\n"
            )
        );

        $opts = stream_context_create($opts);

        $response = file_get_contents($url, false, $opts);

        if (!$response) {
            exit("Request failed");
        }

        return $response;
    }

    public function post(string $url, string $data)
    {
        $curl = curl_init();

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_HEADER, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $headers = array(
            "Accept: application/json",
            "Content-Type: application/json",
            "User-Agent: " . self::USER_AGENT,
        );
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

        curl_exec($curl);

        curl_close($curl);
    }
}

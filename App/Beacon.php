<?php

declare(strict_types=1);

namespace Evolv;

use Evolv\EvolvContext;


const CLIENT_NAME = 'php-sdk';
const ENDPOINT_PATTERN = "/\/(v\d+)\/\w+\/([a-z]+)$/i";

class Beacon {
    private string $endpoint;
    private EvolvContext $context;
    private HttpClient $httpClient;
    private array $messages = [];
    private bool $v1Events;

    public function __construct(string $endpoint, EvolvContext $context, HttpClient $httpClient)
    {
        $this->endpoint = $endpoint;
        $this->context = $context;
        $this->httpClient = $httpClient;

        preg_match(ENDPOINT_PATTERN, $endpoint, $matches);
        $this->v1Events = $matches && $matches[1] === 'v1' && $matches[2] === 'events';
    }

    private function send($payload) {
        $data = json_encode($payload);
        
        $this->httpClient->post($this->endpoint, $data);

        $this->messages = [];
    }

    private function wrapMessages()
    {
        return [
            'uid' => $this->context->uid,
            'client' => CLIENT_NAME,
            'messages' => $this->messages
        ];
    }

    private function transmit()
    {
        if (!count($this->messages)) {
            return;
        }

        if ($this->v1Events) {
            foreach($this->messages as $message) {
                $editedMessage = $message;
                $editedMessage = $message['payload'];
                $editedMessage['type'] = $message['type'];

                $this->send($editedMessage);
            }
        } else {
            $this->send($this->wrapMessages());
        }
    }

    public function emit(string $type, $payload, bool $flush = false)
    {
        $this->messages[] = [
            'type' => $type,
            'payload' => empty($payload) ? new \stdClass() : $payload,
            'timestamp' => time()
        ];

        $this->transmit();
    }

    public function flush() {
        $this->transmit();
    }
}

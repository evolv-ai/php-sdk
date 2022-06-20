<?php

declare(strict_types=1);

namespace App\EvolvClient;

use  App\EvolvStore\Store;
use  App\EvolvContext\Context;
use  App\EvolvOptions\Options;


require 'vendor/autoload.php';
require_once __DIR__ . '/EvolvStore.php';
require_once __DIR__ . '/EvolvContext.php';
//require_once __DIR__ . '/EvolvOptions.php';

ini_set('display_errors', 'on');


class EvolvClient extends Store
{
    public $initialized = false;
    public $options;
    public $store;
    public $obj;
    public $context;
    public $error;
    public $beaconOptions = [];
    public $remoteContext;
    public $localContext;


    public function setOptions($options)
    {

        $this->options = Options::buildOptions($options);

        return $this->options;
    }

    public function beaconOptions($options)
    {

        $options = Options::Parse($options);

        $this->beaconOptions = [

            "blockTransmit" => $options["bufferEvents"],
            "clientName" => $options["clientName"],

        ];

        return json_encode(($this->beaconOptions));
    }

    public function initialize($options, $uid, $remoteContext, $localContext)
    {
        $this->pull($options);

        $this->getActiveKeys();
    }

    public function __construct($options)
    {
        $options = Options::buildOptions($options);

        $options = Options::Parse($options);

        $context = new Context();

        $store =  new Store();

        $context->initialize($options, $options['uid'], $this->remoteContext, $this->localContext);

        $store->initialized($context, $options);

        $store->pull($options);

        $store->getActiveKeys();
    }

}






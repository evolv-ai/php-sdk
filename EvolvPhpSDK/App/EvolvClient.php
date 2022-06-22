<?php

declare(strict_types=1);

namespace App\EvolvClient;

use  App\EvolvStore\Store;
use  App\EvolvContext\Context;
use  App\EvolvOptions\Options;


require 'vendor/autoload.php';
require_once __DIR__ . '/EvolvStore.php';
require_once __DIR__ . '/EvolvContext.php';


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

    /**
     * Initializes the client with required context information.
     *
     * @param {String} uid A globally unique identifier for the current participant.
     * @param {String} sid A globally unique session identifier for the current participant.
     * @param {Object} remoteContext A map of data used for evaluating context predicates and analytics.
     * @param {Object} localContext A map of data used only for evaluating context predicates.
     */

    public function initialize($options, $uid, $remoteContext, $localContext)
    {
        $options = Options::buildOptions($options);

        $options = Options::Parse($options);

        $this->pull($options);

        if ($this->initialized == true) {

            echo('Evolv: Client is already initialized');

        }

        if (!$uid) {

            echo 'Evolv: "uid" must be specified';

        }

        Context::initialize($uid, $remoteContext, $localContext);

        $store = new Store();

        $store->initialized($this->context, $options);

    }

    public function set($key, $value, $local)
    {

        $result = $this->context->set($key, $value, $local);

        return $result;

    }



    public function __construct($options)
    {
        $options = Options::buildOptions($options);

        $options = Options::Parse($options);

        $this->context = new Context();

        $store = new Store();

         $this->context->initialize($options, $options['uid'], $this->remoteContext, $this->localContext);

        $store->initialized($this->context, $options);

        $store->pull($options);

    }

    public function print_r($arr)
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }

}







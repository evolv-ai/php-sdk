<?php

namespace App\EvolvClient;

use  App\EvolvPredicate\Predicate;
use  App\EvolvStore\Store;
use  App\EvolvContext\Context;
use  App\EvolvOptions\Options;
use  App\EvolvBeacon\Beacon;

require 'vendor/autoload.php';
require_once __DIR__ . '/EvolvStore.php';
require_once __DIR__ . '/EvolvBeacon.php';

ini_set('display_errors', 'on');

/**
 * The EvolvClient provides a low level integration with the Evolv participant APIs.
 *
 * The client provides asynchronous access to key states, values, contexts, and configurations.
 *
 * @constructor
 */

class EvolvClient extends Store
{
    /**
     * @ignore
     */
    public $initialized = false;
    /**
     * @ignore
     */
    public $options;
    /**
     * @ignore
     */
    public $store;
    /**
     * @ignore
     */
    public $obj;
    /**
     * @ignore
     */
    public $context;
    /**
     * @ignore
     */
    public $error;
    /**
     * @ignore
     */
    public $beaconOptions = [];
    /**
     * @ignore
     */
    public $remoteContext;
    /**
     * @ignore
     */
    public $localContext;
    /**
     * @ignore
     */
    public $liseners = [];
    /**
     * @ignore
     */
    public $get_liseners = [];


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
     * This is a summary
     * Initializes the client with required context information.
     *
     * @param string $environment String.
     * @param string $uid A globally unique identifier for the current participant.
     * @param string $endpoint Url.
     * @param object $remoteContext A map of data used for evaluating context predicates and analytics.
     * @param object $localContext A map of data used only for evaluating context predicates.
     */

    public function initialize($environment, $uid, $endpoint, $remoteContext, $localContext)
    {

        $this->endpoint = $endpoint;

        $this->environment = $environment;

        $this->uid = $uid;

        $this->pull($environment, $uid, $endpoint);


        if ($this->initialized == true) {

            echo('Evolv: Client is already initialized');

        }

        if (!$uid) {

            echo 'Evolv: "uid" must be specified';

        }

        Context::initialize($uid, $remoteContext, $localContext);

        $store = new Store();

        $store->initialized($this->context, $uid, $endpoint);


    }

    public function set($key, $value, $local)
    {

        Context::set($key, $value, $local);

        $result = $this->getActiveKeys();

        foreach ($this->liseners as $listener) {

            $listener($result);

        }

        foreach ($this->get_liseners as $key => $listener) {

            if (in_array($key, $result)){

                $listener($this->value[$key]);

            }

        }


    }

    /**
     * This is a summary
     * Check all active keys that start with the specified prefix.
     *
     * @param string $lisener function for active keys for get and check.
     *
     */
    public function getActiveKeys($lisener = null)
    {
        if (isset($lisener)) {

            $this->liseners[] = $lisener;

        }

        return parent::getActiveKeys();
    }

    public function get($key, $lisener = null)
    {

        if (isset($lisener)) {


            $this->get_liseners[$key] = $lisener;

        }

        $this->value = parent::get($key);

        return $this->value[$key];
    }

    function getConfig($key, $lisener = null)
    {

        return parent::getConfig($key);

    }

    public function __construct($environment, $uid, $endpoint)
    {

        $this->context = new Context();

        $store = new Store();
        Context::initialize($uid, $this->remoteContext, $this->localContext);

        //  $this->remoteContext = Context::$remoteContext;

        //  $this->localContext = Context::$localContext;

        //   $this->print_r($this->localContext);

        //   $store->initialized($this->context, $options);

        //  $store->pull($options);


    }

    /**
     * @ignore
     */
    public function print_r($arr)
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }

}







<?php

namespace App\EvolvStore;

use  App\EvolvOptions\Options;
use  App\EvolvContext\Context;

require $_SERVER['DOCUMENT_ROOT'] . '/vendor/autoload.php';
require_once __DIR__ . '/EvolvOptions.php';
require_once __DIR__ . '/EvolvContext.php';

class Store
{
    private const GENOME_REQUEST_SENT = 'genome.request.sent';
    private const CONFIG_REQUEST_SENT = 'config.request.sent';
    private const GENOME_REQUEST_RECEIVED = 'genome.request.received';
    private const CONFIG_REQUEST_RECEIVED = 'config.request.received';
    private const REQUEST_FAILED = 'request.failed';
    private const GENOME_UPDATED = 'genome.updated';
    private const CONFIG_UPDATED = 'config.updated';
    private const EFFECTIVE_GENOME_UPDATED = 'effective.genome.updated';
    private const STORE_DESTROYED = 'store.destroyed';


    public $version;
    public $prefix;
    public $keyId;
    public $key;


    public $genomeKeyStates = [];
    public $configKeyStates = [];
    public $context = ' ';
    public $clientContext = null;
    public $initialized = false;
    public $waitingToPull = false;
    public $waitingToPullImmediate = true;
    public $genomes = [];
    public $effectiveGenome = [];
    public $allocations = null;
    public $config = null;
    public $activeEids = [];
    public $activeKeys = [];
    public $previousKeys = [];
    public $activeVariants = [];
    public $previousVariants = [];
    public $genomeFailed = false;
    public $configFailed = false;

    public function __construct($options)
    {
        $options = Options::buildOptions($options);
        $options = Options::Parse($options);
            echo "<pre>";
            print_r($options);
            echo "</pre>";
        $this->version = $options['version'] || 1;
        $this->prefix = $options['endpoint'] . '/' . $options['environment'];
        $this->keyId = $options['auth'][0]['id'];
        $this->key = $options['auth'][0]['secret'];
        $this->$context = ' ';
        $this->$clientContext = null;
        $this->$initialized = false;
        $this->$waitingToPull = false;
        $this->$waitingToPullImmediate = true;
        $this->$genomes = (object)[];
        $this->$effectiveGenome = (object)[];
        $this->$allocations = null;
        $this->$config = null;
        $this->$activeEids = new Set();
        $this->$activeKeys = new Set();
        $this->$previousKeys = new Set();
        $this->$activeVariants = new Set();
        $this->$previousVariants = new Set();
        $this->$genomeFailed = false;
        $this->$configFailed = false;


        $this->genomeKeyStates = [
            'needed' => new Set(),
            'requested' => new Set(),
            'experiments' => new Map(),
        ];

        $this->configKeyStates = [
            'needed' => new Set(),
            'requested' => new Set(),
            'experiments' => new Map(),
        ];

        $outstandingValuePromises = [];
        $outstandingConfigPromises = [];
        $subscriptions = new Set();

    }

}

class Set
{

}

class Map
{

}



<?php

namespace App\EvolvStore;

use  App\EvolvOptions\Options;

require_once __DIR__ . '/EvolvOptions.php';
require_once __DIR__ . '/EvolvContext.php';

class Store
{
    private const GENOME_REQUEST_SENT = "genome.request.sent";
    private const CONFIG_REQUEST_SENT = "config.request.sent";
    private const GENOME_REQUEST_RECEIVED = "genome.request.received";
    private const CONFIG_REQUEST_RECEIVED = "config.request.received";
    private const REQUEST_FAILED = "request.failed";
    private const GENOME_UPDATED = "genome.updated";
    private const CONFIG_UPDATED = "config.updated";
    private const EFFECTIVE_GENOME_UPDATED = "effective.genome.updated";
    private const STORE_DESTROYED = "store.destroyed";


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
    public $allocation = " ";
    public $config = " ";
    public $activeEids = [];
    public $activeKeys = [];
    public $previousKeys = [];
    public $activeVariants = [];
    public $previousVariants = [];
    public $predicate = [];
    public $genomeFailed = false;
    public $configFailed = false;
    public $current = [];
    public $previos = [];


    //public $initialized = false;


    public function pull($options)
    {
        $opts = array(
            'https' => array(
                'method' => "GET",
                'header' => "Content-type: application/json \r\n"
            )
        );

        $keyId = $options['auth'][0]['id'];
        $key = $options['auth'][0]['secret'];
        $allocation = $options['endpoint'] . '/' . $options['environment'] . '/' . $options['uid'] . '/allocations';
        $config = $options['endpoint'] . '/' . $options['environment'] . '/' . $options['uid'] . '/configuration.json';

        $opts = stream_context_create($opts);

        $this->alocation = file_get_contents($allocation, false, $opts);

        $this->config = file_get_contents($config, false, $opts);

        if (!$this->config || !$this->alocation) {

            exit("Not active path or configuration!");

        }

        $this->config = json_decode($this->config, true);

        $this->genomeKeyStates = [
            'needed' => [],
            'requested' => [],
            'experiments' => [],
        ];

        $this->configKeyStates = [
            'needed' => [],
            'requested' => [],
            'experiments' => [],
        ];

        array_push($this->genomeKeyStates['experiments'], $this->config);

        foreach ($this->config['_experiments'] as $key => $exp) {

            array_push($this->configKeyStates['experiments'], $exp);

        }


    }

    public function print_r($arr)
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }


    public function getKeys($array, $point)
    {

        $i = 0;

        if (is_array($array)) {
            foreach ($array as $key => $value) {
                $web = key($value);
                $index = array_keys($value[$web]);
            }
        }

        if (is_array($array)) {

            foreach ($array as $key => $value) {

                foreach ($array[$i][$web] as $key => $value) {

                    if (is_array($value) && array_key_exists($point, $value) && $value[$point] == 1) {

                        $this->current[] = $web . "." . $key;

                        $this->predicate[$key] = $value['_predicate'];

                    } elseif (is_array($value) && array_key_exists($point, $value) && $value[$point] != 1) {

                        $this->previous[] = $web . "." . $key;
                    }

                    if (is_array($value)) {

                        foreach ($value as $keys => $exp) {

                            if (is_array($exp)) {

                                $this->predicate[$keys] = $value['_predicate'];

                                if (is_array($exp) && array_key_exists($point, $exp) && $exp[$point] === 1) {

                                    $this->current[] = $web . "." . $key . "." . $keys;

                                } else {

                                    if (!preg_match('/[_]/i', $keys)) {

                                        $this->previous[] = $web . "." . $key . "." . $keys;

                                    };
                                }
                            }
                        }
                    }
                }
                $i++;
            }
        }
    }


    public function getActiveKeys()
    {
        $configKey = $this->configKeyStates['experiments'];

        $p = 0;
        $this->getKeys($this->configKeyStates['experiments'], $point = "_is_entry_point");


        echo "<strong>Current:</strong><br>" . implode('<br>', $this->current);
        echo "<br>";
        echo "<strong>Previous:</strong><br>" . implode('<br>', $this->previous);
    }

    public function evaluateAllocationPredicates()
    {

    }

    public function initialized($context, $options)
    {

        if ($this->initialized) {

            ('Evolv: The store has already been initialized.');

        }
        $context = $this->context;


    }


}





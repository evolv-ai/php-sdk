<?php

namespace App\EvolvStore;

use  App\EvolvOptions\Options;

require_once __DIR__ . '/EvolvOptions.php';
require_once __DIR__ . '/EvolvContext.php';

class Store
{

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
    public $current = [];
    public $previos = [];
    public $prev = null;
    public $web = "web";
    public $point = "_is_entry_point";


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

        $arr_location = file_get_contents($allocation, false, $opts);

        $arr_config = file_get_contents($config, false, $opts);

        if (!$arr_config || !$arr_location) {

            exit("Not active path or configuration!");

        }

        $arr_config = json_decode($arr_config, true);

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

        array_push($this->genomeKeyStates['experiments'], $arr_config);

        foreach ($arr_config['_experiments'] as $key => $v) {

            array_push($this->configKeyStates['experiments'], $v);

        }

    }

    public function print_r($arr)
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }


    public function getActiveKeyses($array)
    {
        $web = "web";

        foreach ($array as $key => $value) {

            if (is_array($value)) {

                $value = array_reverse($value);

                $this->getActiveKeyses($value);

                if (is_numeric($key)) continue;

                if (!preg_match('/[_]/i', $key) && !empty($key) && $key != 'rules') {

                    if ($key == $this->web) {
                        continue;
                    } else if (array_key_exists($this->point, $value) && $value[$this->point] == 1) {

                        $this->prev = $key;

                        $this->previos[] = $this->web . '.' . $this->prev;

                        $this->current[] = $this->web . '.' . $this->prev;

                    } else {
                        $this->current[][] = $key;

                        $this->previos[][] = $key;
                    }

                }
            }

        }

    }

    public function setActiveKeys($array)
    {
        foreach ($this->current as $k => $val) {

            if (!is_array($val)) {

                $arr[] = $y = $val;

            } else {

                foreach ($val as $k => $t) {

                    $arr[] = $y . "." . $t;
                }
            }
        }
        $this->print_r($arr);
    }

    public function getActiveKeys()
    {
        $experiments = $this->configKeyStates['experiments'];

        $experiments = array_reverse($experiments);

        $this->getActiveKeyses($experiments);

        $this->current = array_reverse($this->current);

        $this->previos = array_reverse($this->previos);

        $this->setActiveKeys($this->current);

        $this->setActiveKeys($this->previos);

    }

    public function evaluateAllocationPredicates()
    {
        $this->print_r($this->configKeyStates);
    }

    public function initialized($context, $options)
    {

        if ($this->initialized) {


           echo 'Evolv: The store has already been initialized.';

        }


        $context = $this->context;

    }


}





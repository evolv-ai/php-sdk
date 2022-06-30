<?php

namespace App\EvolvStore;

use App\EvolvContext\Context;
use  App\EvolvOptions\Options;
use  App\EvolvPredicate\Predicate;
use Faker\Provider\Person;

require_once __DIR__ . '/EvolvOptions.php';
require_once __DIR__ . '/EvolvContext.php';
require_once __DIR__ . '/EvolvPredicate.php';

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

    public function pull($environment,$uid,$endpoint)
    {
        $opts = array(
            'https' => array(
                'method' => "GET",
                'header' => "Content-type: application/json \r\n"
            )
        );

        $allocation = $endpoint . '/' . $environment . '/' . $uid . '/allocations';

        $config = $endpoint . '/' . $environment . '/' . $uid . '/configuration.json';

        $opts = stream_context_create($opts);

        $arr_location = file_get_contents($allocation, false, $opts);

        $arr_config = file_get_contents($config, false, $opts);

        if (!$arr_config && !$arr_location) {

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

    public function getActiveKeys()
    {

        $predicate = new Predicate();

        $configKeyStates = $this->configKeyStates;

       // $predicates = $predicate->getPredicate($configKeyStates);

        $context = $this->localContext();

        $keys = $predicate->evaluate($context,  $configKeyStates );

        return $keys;
    }

    public function localContext(){

        return Context::locContext();

    }

    public function remoteContext(){

        return Context::remContext();

    }

    public function evaluatePredicates($context, $config)
    {

        if (empty($config) || count($config) == 0) {

            echo "Config empty!";

            return false;

        }

        $predicate = new Predicate();

        $predicates = $predicate->getPredicate($config);

        $predicate->evaluate($context, $predicates);

    }


    public function print_r($arr)
    {
        echo "<pre>";
        print_r($arr);
        echo "</pre>";
    }

    public function initialized($context, $uid, $endpoint)
    {

        if ($this->initialized) {


            echo 'Evolv: The store has already been initialized.';

        }

        $context = $this->context;

    }

}





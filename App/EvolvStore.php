<?php

namespace App\EvolvStore;

use App\EvolvContext\Context;
use  App\EvolvPredicate\Predicate;
use HttpClient;

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
    private $subscriptions = [];
    public $keys;
    protected $events;
    public $value;
    public $local;

    public function pull($environment, $uid, $endpoint)
    {
        $httpClient = new HttpClient();

        $allocationUrl = $endpoint . '/' . $environment . '/' . $uid . '/allocations';
        $configUrl = $endpoint . '/' . $environment . '/' . $uid . '/configuration.json';

        $arr_location = $httpClient->request($allocationUrl);
        $arr_config = $httpClient->request($configUrl);

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

        $context = Context::locContext();

        $this->keys = $predicate->evaluate($context, $configKeyStates);

        return $this->keys;
    }

    public function listener()
    {

        $result = call_user_func([$this, 'getActiveKeys']);

        return $result;

    }

    public function get($var = null)
    {

     $keys = $this->listener();

     if(in_array($var, $keys)){
         echo "++";
     }
     else{
         echo "--";
     }

    }


    public function localContext()
    {

        return Context::locContext();

    }

    public function remoteContext()
    {

        return Context::remContext();

    }

    public function evaluatePredicates($context, $config)
    {

        if (empty($config) || count($config) == 0) {

            echo "Config empty!";

            return false;

        }

        $predicate = new Predicate();

        $predicates = $predicate->getPredicate($context, $config);

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





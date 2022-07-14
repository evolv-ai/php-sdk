<?php

namespace App\EvolvStore;

use  App\EvolvContext\Context;
use  App\EvolvPredicate\Predicate;
use  App\EvolvBeacon\Beacon;
use HttpClient;
use phpDocumentor\Reflection\Types\Boolean;

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
    public $context;
    public $clientContext = null;
    public $initialized = false;
    public $waitingToPull = false;
    public $waitingToPullImmediate = true;
    public $genomes = [];
    public $effectiveGenome = [];
    public $allocations = null;
    public $config = null;
    public $current = [];
    public $keys;
    public $value;
    public $local;
    public $get = [];
    public $remoteContext;
    public $localContext;
    public $data;
    public $flesh_data = [];


    public function pull($environment, $uid, $endpoint)
    {
        $httpClient = new HttpClient();

        $allocationUrl = $endpoint . '/' . $environment . '/' . $uid . '/allocations';
        $configUrl = $endpoint . '/' . $environment . '/' . $uid . '/configuration.json';

        $arr_location = $httpClient->request($allocationUrl);
        $arr_config = $httpClient->request($configUrl);
        $arr_location = json_decode($arr_location, true);
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

        array_push($this->genomeKeyStates['experiments'], $arr_location);

        foreach ($arr_config['_experiments'] as $key => $v) {

            array_push($this->configKeyStates['experiments'], $v);

        }
    }

    public function getActiveKeys()
    {

        $predicate = new Predicate();

        $configKeyStates = $this->configKeyStates;

        $context = $this->localContext();

        $this->keys = $predicate->evaluate($context, $configKeyStates);

        return $this->keys;
    }

    public function get($key)
    {

        $config = $this->genomeKeyStates;

        $predicate = new Predicate();

        $this->value = $predicate->valueFromKey($key, $config);

        $this->get[$key] = $this->value;

        return $this->get;

    }

    function getConfig($key)
    {

        $config = $this->configKeyStates;

        $predicate = new Predicate();

        $this->value = $predicate->valueFromKey($key, $config);

        return $this->value;
    }


    public function localContext()
    {
        $this->localContext = Context::$localContext;

        if (is_array($this->data) && !empty($this->data)) {

            $this->localContext = Context::pushToArray($this->data, $this->localContext, true);

        }

        return $this->localContext;

    }

    public function remoteContext()
    {

        $this->remoteContext = Context::$remoteContext;

        $this->remoteContext = $this->reevaluateContext($this->remoteContext);

        if (is_array($this->data) && !empty($this->data)) {

            $this->remoteContext = Context::pushToArray($this->data, $this->remoteContext, false);

        }
        return $this->remoteContext;

    }

    public function setActiveAndEntryKeyStates()
    {
        /*        $predicate = new Predicate();

                $configKeyStates = $this->configKeyStates;

                $context = $this->remoteContext();

                $keys = $predicate->evaluate($context, $configKeyStates);
                */
    }

    public function getUTF16CodeUnits($string)
    {
        $string = substr(json_encode($string), 1, -1);

        preg_match_all("/\\\\u[0-9a-fA-F]{4}|./mi", $string, $matches);

        return $matches[0];
    }

    public function JS_StringLength($string)
    {
        return count($this->getUTF16CodeUnits($string));
    }

    public function JS_charCodeAt($string, $index)
    {
        $utf16CodeUnits = $this->getUTF16CodeUnits($string);

        $unit = $utf16CodeUnits[$index];

        if (strlen($unit) > 1) {

            $hex = substr($unit, 2);

            return hexdec($hex);

        } else {

            return ord($unit);
        }
    }


    public function hashCode($string)
    {

        $ret = 0;

        // $converted = iconv('UTF-8', 'UTF-16LE', $string);
        /*
                for ($i = 0; $i < iconv_strlen($converted, 'UTF-16LE'); $i++) {
                  //  echo $converted[$i] . "<br>";
                    $character = iconv_substr($converted, $i, 1, 'UTF-16LE');
                    $codeUnits = unpack('v', $character);

                    foreach ($codeUnits as $codeUnit) {
                        echo $codeUnit . "<br>";
                      $ret = (31 * $ret + $codeUnit) >> 0 ;
                    }
                }*/

        /*  for($i=0; $i<$this->JS_StringLength($string); $i++) {

              $ret = (31 * $ret + $this->JS_charCodeAt($string, $i)) >> 0;

          }*/

        /*    for ($i = 0; $i < strlen($converted); $i += 2) {
                $codeUnit = ord($converted[$i]) + (ord($converted[$i+1]) << 0);
                $ret = $codeUnit . PHP_EOL;
            }*/
        //echo  $ret;
        return $ret;
    }

    public function reevaluateContext($context)
    {
        $revoluate = [
            'keys' => ['active' => []],
            'variants' => ['active' => []],
        ];

        $predicate = new Predicate();

        $this->setActiveAndEntryKeyStates();

        $configKeyStates = $this->configKeyStates;

        $keys = $predicate->evaluate($context, $configKeyStates);

        $revoluate['keys']['active'] += $keys;

        if (empty($context) && !is_array($context)) {

            return false;

        }

        foreach ($keys as $key => $val) {

            $value = $predicate->valueFromKeyRevoluate($val, $this->genomeKeyStates);

            if (is_array($value)) {

                $vals[] = '';
            }

            $vals[] = $val . " : " . $this->hashCode($value);
        }
        $revoluate['variants']['active'] += $vals;

        return $revoluate;
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

    public function emit($type, $data, $flash = false)
    {

        $environment = $this->environment;

        $endpoint = $this->endpoint;

        $uid = $this->uid;

        $data = [
            'type' => $type,
            'metadata' => $data,
            'uid' => $uid,
            'boolean' => $flash,
            'time' => time() * 1000,
        ];

        $this->data[] = [
            'type' => $type,
            'timestamp' => time() * 1000,
            $type => $flash,
        ];
        if($flash == false) {

            $this->flesh_data[] = $data;
        }

        $beacon = new Beacon();

        $beacon->emit($environment, $endpoint, $data, $this->flesh_data,  $flash);

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





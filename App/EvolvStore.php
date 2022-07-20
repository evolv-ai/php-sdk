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
    /**
     * @ignore
     */
    public $version;
    /**
     * @ignore
     */
    public $prefix;
    /**
     * @ignore
     */
    public $keyId;
    /**
     * @ignore
     */
    public $key;

    /**
     * @ignore
     */
    public $genomeKeyStates = [];
    /**
     * @ignore
     */
    public $configKeyStates = [];
    /**
     * @ignore
     */
    public $context;
    /**
     * @ignore
     */
    public $clientContext = null;
    /**
     * @ignore
     */
    public $initialized = false;
    /**
     * @ignore
     */
    public $waitingToPull = false;
    /**
     * @ignore
     */
    public $waitingToPullImmediate = true;
    /**
     * @ignore
     */
    public $genomes = [];
    /**
     * @ignore
     */
    public $effectiveGenome = [];
    /**
     * @ignore
     */
    public $allocations = null;
    /**
     * @ignore
     */
    public $config = null;
    /**
     * @ignore
     */
    public $current = [];
    /**
     * @ignore
     */
    public $keys;
    /**
     * @ignore
     */
    public $value;
    /**
     * @ignore
     */
    public $local;
    /**
     * @ignore
     */
    public $get = [];
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
    public $data;
    /**
     * @ignore
     */
    public $flesh_data = [];

    /**
     * @ignore
     */
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
       // $this->print_r($this->configKeyStates['experiments']);

    }

    /**
     * This is a summary
     * Check all active keys that start with the specified prefix.
     *
     * @param string $lisener function for active keys for get and check.
     *
     */

    public function getActiveKeys()
    {

        $predicate = new Predicate();

        $configKeyStates = $this->configKeyStates;

        $context = $this->localContext();
        //$this->print_r( $context);

        $this->keys = $predicate->evaluate($context, $configKeyStates);

        return $this->keys;
    }

    /**
     * This is a summary
     * Get the value of a specified key.
     *
     * @param string $key The key of the value to retrieve.
     *
     */
    public function get($key)
    {

        $config = $this->genomeKeyStates;

        $predicate = new Predicate();

        $this->value = $predicate->valueFromKey($key, $config);

        $this->get[$key] = $this->value;

        return $this->get;

    }

    /**
     * This is a summary
     * Get the configuration for a specified key.
     *
     * @param string $key The key to retrieve the configuration for.
     *
     */
    function getConfig($key)
    {

        $config = $this->configKeyStates;

        $predicate = new Predicate();

        $this->value = $predicate->valueFromKey($key, $config);

        return $this->value;
    }

    /**
     * @ignore
     */
    public function localContext()
    {
        $this->localContext = Context::$localContext;

        if (is_array($this->data) && !empty($this->data)) {

            $this->localContext = Context::pushToArray($this->data, $this->localContext, true);

        }

        return $this->localContext;

    }

    /**
     * @ignore
     */
    public function remoteContext()
    {

        $this->remoteContext = Context::$remoteContext;

        $this->remoteContext = $this->revaluateContext($this->remoteContext);

        if (is_array($this->data) && !empty($this->data)) {

            $this->remoteContext = Context::pushToArray($this->data, $this->remoteContext, false);

        }
        return $this->remoteContext;

    }

    /**
     * @ignore
     */
    public function setActiveAndEntryKeyStates()
    {
        /*        $predicate = new Predicate();

                $configKeyStates = $this->configKeyStates;

                $context = $this->remoteContext();

                $keys = $predicate->evaluate($context, $configKeyStates);
                */
    }

    /**
     * @ignore
     */
    public function getUTF16CodeUnits($string)
    {
        $string = substr(json_encode($string), 1, -1);

        preg_match_all("/\\\\u[0-9a-fA-F]{4}|./mi", $string, $matches);

        return $matches[0];
    }

    /**
     * @ignore
     */
    public function JS_StringLength($string)
    {
        return count($this->getUTF16CodeUnits($string));
    }

    /**
     * @ignore
     */
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

    /**
     * @ignore
     */
    function uniord($u) {
        $k = mb_convert_encoding($u, 'UCS-2LE', 'UTF-8');
        $k1 = ord(substr($k, 0, 1));
        $k2 = ord(substr($k, 1, 1));
        return $k2 * 256 + $k1;
    }

    /**
     * @ignore
     */
    public function hashCode($string)
    {
        $ret = 0;

        if (is_array($string)) {

            $string = json_encode($string);

        }


        $converted = iconv('UTF-8', 'UTF-16LE', $string);

        for ($i = 0; $i < iconv_strlen($converted, 'UTF-16LE'); $i++) {

            $character = iconv_substr($converted, $i, 1, 'UTF-16LE');

            $codeUnits = unpack('v', $character);

            foreach ($codeUnits as $codeUnit) {

                $ret = (31 * $ret + $codeUnit) << 2;
            }
        }

/*        for ($i = 0; $i < $this->JS_StringLength($string); $i++) {

            $ret = (31 * $ret + $this->JS_charCodeAt($string, $i));

        }

        for ($i = 0; $i < strlen($converted); $i += 2) {
            $codeUnit = ord($converted[$i]) + (ord($converted[$i + 1]) << 0);
            $ret = $codeUnit . PHP_EOL;
        }*/

        return $ret;
    }

    /**
     * Reevaluates the current context.
     */
    public function revaluateContext($context)
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

            $vals[] = $val . ":" . $this->hashCode($value);
        }
        $revoluate['variants']['active'] += $vals;

        return $revoluate;
    }

    /**
     * @ignore
     */
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

    /**
     * This is a summary
     * Send an event to the events endpoint.
     *
     * @param string $type The type associated with the event.
     * @param object $data Any metadata to attach to the event.
     * @param boolean $flash If true, the event will be sent immediately.
     *
     */
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
            //$type => $flash,
        ];
        if ($flash == false) {

            $this->flesh_data[] = $data;
        }

        $beacon = new Beacon();

        $beacon->emit($environment, $endpoint, $data, $this->flesh_data, $flash);

    }

    /**
     * This is a summary
     * Force all beacons to transmit.
     *
     */
    public function flush()
    {


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

    /**
     * @ignore
     */
    public function initialized($context, $uid, $endpoint)
    {

        if ($this->initialized) {


            echo 'Evolv: The store has already been initialized.';

        }

        $context = $this->context;

    }


}





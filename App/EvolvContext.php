<?php

declare(strict_types=1);

namespace App\EvolvContext;

use  App\EvolvOptions\Options;
use App\EvolvStore\Store;

require_once __DIR__ . '/EvolvOptions.php';


class Context
{

    public $uid;
    public $sid;
    public $initialized = false;
    public static $current = [];
    public static $set = [];
    public static $value;
    public static $local;
    public static $context;
    public static $remoteContext;
    public static $localContext;
    public static $result;

    /**
     * A unique identifier for the participant.
     */
    public function getUid($uid)
    {
        return $this->uid = $uid;
    }


    /**
     * The context information for evaluation of predicates and analytics.
     */
    public function remoteContext($remoteContext)
    {

        $this->remoteContext = Options::Parse($remoteContext);

    }

    /**
     * The context information for evaluation of predicates only, and not used for analytics.
     */
    public function localContext($localContext)
    {

        self::$remoteContext = Options::Parse(self::$localContext);

    }

    public function ensureInitialized()
    {

        if ($this->initialized == false) {
            echo 'Evolv: The evolv context is not initialized';
        } else if ($this->initialized == true) {
            echo "Evolv: The evolv context is initialized";
        }
    }

    public static function getValueForKey($key, $local)
    {

        self::$value;
        $local;

        $keys = explode(".", $key);

        for ($i = 0; $i < count($keys); $i++) {

            $k = $keys[$i];

            if ($i === (count($keys) - 1)) {

                self::$current[$k] = self::$value;

                break;

            } else {

                self::$current[$k] = null;

            }

        }

        return self::$value;
    }

    public static function setKeyToValue($key, $value, $local)
    {
        $key;
        $value;

        $array = explode(".", $key);

        $array = array_reverse($array);

        foreach ($array as $key => $val) {

            $setval = ($key === 0) ? $value : self::$result;

            self::$result = [

                $val => $setval

            ];

        }

        return self::$result;

    }

    public static function arraysEqual($a, $b)
    {
        if (!is_array($a) || !is_array($b)) return false;

        if ($a === $b) return true;

        if (count($a) !== count($b)) return false;

        for ($i = 0; $i < count($a); ++$i) {

            if ($a[$i] !== $b[$i]) return false;

        }
        return true;
    }


    /**
     * Sets a value in the current context.
     *
     * Note: This will cause the effective genome to be recomputed.
     *
     * @param key {String} The key to associate the value to.
     * @param value {*} The value to associate with the key.
     * @param local {Boolean} If true, the value will only be added to the localContext.
     */

    public static function set($key, $value, $local)
    {
        $key;
        $value;


        switch ($local) {

            case true:

                self::$localContext[] = self::setKeyToValue($key, $value, $local);

                break;

            case false:

                self::$remoteContext[] = self::setKeyToValue($key, $value, $local);

                break;

        }

    }



    public static function locContext()
    {

        return self::$localContext;

    }

    public static function remContext()
    {

        return self::$remoteContext;

    }


    public static function initialize($uid, $remoteContext, $localContext)
    {
        $context = new Context();

        if ($context->initialized) {

            echo $error = 'Evolv: The context is already initialized';

        }

        $context->initialized = true;
    }



    public function __construct()
    {

    }


}
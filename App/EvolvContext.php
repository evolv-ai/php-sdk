<?php

namespace App\EvolvContext;

//use _HumbugBox7eb78fbcc73e\___PHPSTORM_HELPERS\object;
use  App\EvolvOptions\Options;
use App\EvolvStore\Store;

require_once __DIR__ . '/EvolvOptions.php';

/**
 * The EvolvContext provides functionality to manage data relating to the client state, or context in which the
 * variants will be applied.
 *
 * This data is used for determining which variables are active, and for general analytics.
 *
 * @constructor
 */

class Context
{
    /**
     * @ignore
     */
    public $uid;

    /**
     * @ignore
     */
    public $sid;

    /**
     * @ignore
     */
    public $initialized = false;

    /**
     * @ignore
     */
    public static $current = [];

    /**
     * @ignore
     */
    public static $set = [];

    /**
     * @ignore
     */
    public static $value;

    /**
     * @ignore
     */
    public static $local;

    /**
     * @ignore
     */
    public static $context;
    /**
     * The context information for evaluation of predicates and analytics.
     */
    public static $remoteContext;
    /**
     * The context information for evaluation of predicates only, and not used for analytics.
     */
    public static $localContext;

    /**
     * @ignore
     */
    public static $result;

    /**
     * @ignore
     */
    public static $events;

    /**
     * @ignore
     */
    public function getUid($uid)
    {
        return $this->uid = $uid;
    }

    /**
     * @ignore
     */
    public function remoteContext($remoteContext)
    {

        $this->remoteContext = Options::Parse($remoteContext);

    }

    /**
     * @ignore
     */
    public function localContext($localContext)
    {

        self::$remoteContext = Options::Parse(self::$localContext);

    }

    /**
     * @ignore
     */
    public function ensureInitialized()
    {

        if ($this->initialized == false) {

            echo 'Evolv: The evolv context is not initialized';

        } else if ($this->initialized == true) {

            echo "Evolv: The evolv context is initialized";

        }
    }

    /**
     * @ignore
     */
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

    /**
     * @ignore
     */
    public static function setKeyToValue($key, $value, $local)
    {
        $key;
        $value;

        $array = explode(".", $key);

        $array = array_reverse($array);

        if (!is_array(self::$result)) {

            foreach ($array as $key => $val) {

                $setval = ($key === 0) ? $value : self::$result;

                self::$result = [

                    $val => $setval

                ];

            }
        } else {

            foreach (self::$result as $key => $val) {

                $arr = array_diff($array, [$key]);

                $key_v = implode("", $arr);

                if (array_key_exists($key_v, self::$result[$key]) == true) {

                    unset(self::$result[$key][$key_v]);

                };

                self::$result[$key] += [$key_v => $value];

            }

        }

        return self::$result;

    }

    /**
     * @ignore
     */
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
     * This is a summary
     * Sets a value in the current context.
     *
     * Note: This will cause the effective genome to be recomputed.
     *
     * @param string $key The key to associate the value to.
     * @param string $value The value to associate with the key.
     * @param boolean $local If true, the value will only be added to the localContext.
     *
     */
    public static function set($key, $value, $local)
    {
        $key;
        $value;


        switch ($local) {

            case true:

                self::$localContext = self::setKeyToValue($key, $value, $local);

                break;

            case false:

                self::$remoteContext = self::setKeyToValue($key, $value, $local);

                break;

        }

    }

    /**
     * @ignore
     */
    public static function pushToArray($data, $context, $local)
    {
        // echo $local;

        if (empty($context) && !is_array($context)) {

            $context = [];

        }

        if ($local == true) {

            foreach ($data as $key => $value) {

                foreach ($value as $k => $item) {

                    if ($k == "type" || $k == "timestamp") {
                        unset($data[$key][$k]);
                    }

                }
            }

            self::$events = ['events' => $data];

        } else {

            self::$events = ['events' => $data];

        }

        $context += self::$events;

        return $context;
    }

    /**
     * @ignore
     */
    public static function initialize($uid, $remoteContext, $localContext)
    {
        $context = new Context();

        if ($context->initialized) {

            echo $error = 'Evolv: The context is already initialized';

        }

        $context->initialized = true;
    }

}
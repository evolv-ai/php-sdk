<?php

declare(strict_types=1);

namespace App\EvolvContext;

use  App\EvolvOptions\Options;

require_once __DIR__ . '/EvolvOptions.php';
require_once __DIR__ . '/EvolvClient.php';

class Context
{

    public $uid;
    public $sid;
    public $remoteContext;
    public $localContext;
    public $initialized = false;
    public $current = [];
    public $set = [];
    public $value;

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

        $this->remoteContext = Options::Parse($localContext);

    }

    public function ensureInitialized()
    {

        if ($this->initialized == false) {
            echo 'Evolv: The evolv context is not initialized';
        } else if ($this->initialized == true) {
            echo "Evolv: The evolv context is initialized";
        }
    }

    public function getValueForKey($key, $local)
    {

        $this->value;

        $keys = explode(".", $key);

        for ($i = 0; $i < count($keys); $i++) {

            $k = $keys[$i];

            if ($i === (count($keys) - 1)) {

                $this->current[$k] = $this->value;

                break;

            } else {
                $this->current[$k] = null;
            }

        }

        return $this->value;
    }

    public function setKeyToValue($key, $value, $local)
    {
        $key;
        $value;
        //   $this->ensureInitialized();
        $keys = explode(".", $key);

        for ($i = 0; $i < count($keys); $i++) {

            $k = $keys[$i];

            if ($i === (count($keys) - 1)) {

                $this->current[$k] = $value;

                break;

            } else {
                $this->current[$k] = ' ';
            }

        }
       // print_r($this->current);
        return $this->current;
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

    public function set($key, $value, $local)
    {
        $key;
        $value;

        $context = $local ? $this->localContext : $this->remoteContext;

        $before = $this->getValueForKey($key, $local);

        $context = $this->setKeyToValue($key, $value, $local);

        return $context;
    }


    public static function initialize($uid, $remoteContext, $localContext)
    {
        $context = new Context();

        if ($context->initialized) {

            echo $error = 'Evolv: The context is already initialized';

        }

        $context->remoteContext = $context->remoteContext ? Options::Parse($context->remoteContext) : [];
        $context->localContext = $context->remoteContext ? Options::Parse($context->localContext) : [];
        $context->initialized = true;
    }


}
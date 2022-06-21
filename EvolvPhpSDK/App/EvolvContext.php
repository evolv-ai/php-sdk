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
        if (!($this->initialized)) {
            echo 'Evolv: The evolv context is not initialized';
        }
    }

    public function setKeyToValue($key, $val, $loc)
    {
        $mass = [];
        $this->array = array_push( $mass, $key);
        for ($i = 0; $i < $this->array; $i++) {
            $i . ":"  ;
        }


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
        echo $key;
        echo $value;
        $this->ensureInitialized();

        $context = $local ? $this->localContext : $this->remoteContext;

        $cnt = $this->setKeyToValue($key, $value, $context);

        print_r($cnt);
    }


    public function initialize($uid, $remoteContext, $localContext)
    {

        if ($this->initialized) {

            echo $error = 'Evolv: The context is already initialized';

        }

        $this->remoteContext = $this->remoteContext ? Options::Parse($this->remoteContext) : [];
        $this->localContext = $this->remoteContext ? Options::Parse($this->localContext) : [];
        $this->initialized = true;
    }


}
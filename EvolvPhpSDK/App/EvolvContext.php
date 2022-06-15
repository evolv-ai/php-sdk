<?php

namespace App\EvolvContext;

use  App\EvolvOptions\Options;

require_once __DIR__ . '/EvolvOptions.php';

class Context
{

    private const CONTEXT_CHANGED = 'context.changed';
    private const CONTEXT_INITIALIZED = 'context.initialized';
    private const CONTEXT_VALUE_REMOVED = 'context.value.removed';
    private const CONTEXT_VALUE_ADDED = 'context.value.added';
    private const CONTEXT_VALUE_CHANGED = 'context.value.changed';
    private const CONTEXT_DESTROYED = 'context.destroyed';
    private const DEFAULT_QUEUE_LIMIT = 50;

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

    function ensureInitialized()
    {
        if ($this->initialized) {

            $error = 'Evolv: The evolv context is not initialized';

           return $error;
        }
    }


    public function initialize($uid, $remoteContext, $localContext)
    {
        if ($this->initialized) {

            echo $error = 'Evolv: The context is already initialized';
            exit();

        }

        $this->remoteContext = $this->remoteContext ? Options::Parse($this->remoteContext) : [];
        $this->localContext = $this->remoteContext ? Options::Parse($this->localContext) : [];
        $this->initialized = true;
    }


}
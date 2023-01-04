<?php

declare(strict_types=1);

namespace Evolv;

use function Evolv\Utils\waitFor;
use function Evolv\Utils\emit;

/**
 * The EvolvClient provides a low level integration with the Evolv participant APIs.
 * 
 * The client provides asynchronous access to key states, values, contexts, and configurations.
 * 
 */
class EvolvClient
{
    const INITIALIZED = 'initialized';
    const CONFIRMED = 'confirmed';
    const CONTAMINATED = 'contaminated';
    const EVENT_EMITTED = 'event.emitted';

    public bool $initialized = false;
    /**
     * The context against which the key predicates will be evaluated.
     */
    public EvolvContext $context;
    public HttpClient $httpClient;
    private EvolvStore $store;
    private bool $autoconfirm;
    private Beacon $contextBeacon;
    private Beacon $eventBeacon;

    /**
     * @param string $environment The current environment id.
     * @param string $endpoint The participants API endpoint.
     * @param bool $autoconfirm Optional. True by default. The autoconfirm flag.
     * @return object
     */
    public function __construct(string $environment, string $endpoint = 'https://participants.evolv.ai/', bool $autoconfirm = true, $httpClient = null)
    {
        $this->httpClient = $httpClient ?? new HttpClient();

        $this->context = new EvolvContext();
        $this->store = new EvolvStore($environment, $endpoint, $this->httpClient);
        
        $this->contextBeacon = new Beacon($endpoint . 'v1/' . $environment . '/data', $this->context, $this->httpClient);
        $this->eventBeacon = new Beacon($endpoint . 'v1/' . $environment . '/events', $this->context, $this->httpClient);
        
        $this->autoconfirm = $autoconfirm;
    }

    /**
     * Initializes the client with required context information.
     *
     * @param string $uid A globally unique identifier for the current participant.
     * @param array $remoteContext A map of data used for evaluating context predicates and analytics.
     * @param array $localContext A map of data used only for evaluating context predicates.
     */
    public function initialize(string $uid, array $remoteContext = [], array $localContext = [])
    {
        if ($this->initialized) {
            throw new \Exception('Evolv: Client is already initialized');
            exit('Evolv: Client is already initialized');
        }

        if (!$uid) {
            throw new \Exception('Evolv: "uid" must be specified');
            exit('Evolv: "uid" must be specified');
        }

        waitFor(CONTEXT_INITIALIZED, function($type, $ctx) {
            $this->contextBeacon->emit($type, $this->context->remoteContext);
        });

        waitFor(CONTEXT_VALUE_ADDED, function($type, $key, $value, $local) {
            if ($local) {
                return;
            }
            $this->contextBeacon->emit($type, ['key' => $key, 'value' => $value]);
        });
        waitFor(CONTEXT_VALUE_CHANGED, function($type, $key, $value, $before, $local) {
            if ($local) {
                return;
            }
            $this->contextBeacon->emit($type, ['key' => $key, 'value' => $value]);
        });
        waitFor(CONTEXT_VALUE_REMOVED, function ($type, $key, $local) {
            if ($local) {
                return;
            }
            $this->contextBeacon->emit($type, ['key' => $key]);
        });

        $this->context->initialize($uid, $remoteContext, $localContext);
        $this->store->initialize($this->context);

        if ($this->autoconfirm) {
            $this->confirm();
        }

        $this->initialized = true;

        emit(EvolvClient::INITIALIZED);
    }

    /**
     * Add listeners to lifecycle events that take place in to client.
     *
     * Currently supported events:
     * * "initialized" - Called when the client is fully initialized and ready for use with (topic, options)
     * * "context.initialized" - Called when the context is fully initialized and ready for use with (topic, updated_context)
     * * "context.changed" - Called whenever a change is made to the context values with (topic, updated_context)
     * * "context.value.removed" - Called when a value is removed from context with (topic, key, updated_context)
     * * "context.value.added" - Called when a new value is added to the context with (topic, key, value, local, updated_context)
     * * "context.value.changed" - Called when a value is changed in the context (topic, key, value, before, local, updated_context)
     * * "context.destroyed" - Called when the context is destroyed with (topic, context)
     * * "genome.request.sent" - Called when a request for a genome is sent with (topic, requested_keys)
     * * "config.request.sent" - Called when a request for a config is sent with (topic, requested_keys)
     * * "genome.request.received" - Called when the result of a request for a genome is received (topic, requested_keys)
     * * "config.request.received" - Called when the result of a request for a config is received (topic, requested_keys)
     * * "request.failed" - Called when a request fails (topic, source, requested_keys, error)
     * * "genome.updated" - Called when the stored genome is updated (topic, allocation_response)
     * * "config.updated" - Called when the stored config is updated (topic, config_response)
     * * "effective.genome.updated" - Called when the effective genome is updated (topic, effectiveGenome)
     * * "store.destroyed" - Called when the store is destroyed (topic, store)
     * * "confirmed" - Called when the consumer is confirmed (topic)
     * * "contaminated" - Called when the consumer is contaminated (topic)
     * * "event.emitted" - Called when an event is emitted through the beacon (topic, type, score)
     *
     * @param string $topic The event topic on which the listener should be invoked.
     * @param callable $listener The listener to be invoked for the specified topic.
     * @see EvolvClient for listeners that should only be invoked once.
     */
    public function on(string $topic, callable $listener)
    {
        waitFor($topic, $listener);
    }

    /**
     * Send an event to the events endpoint.
     *
     * @param string $type The type associated with the event.
     * @param mixed $metadata  Any metadata to attach to the event.
     * @param bool $flush If true, the event will be sent immediately.
     */
    public function emit(string $type, $metadata, bool $flush = false)
    {
        $this->context->pushToArray('events', ['type' => $type, 'timestamp' => time()]);
        $this->eventBeacon->emit($type, [
            'uid' => $this->context->uid,
            'metadata' => $metadata
        ], $flush);
        emit(EvolvClient::EVENT_EMITTED, $type, $metadata);
    }

    /**
     * Check all active keys that start with the specified prefix.
     *
     * @param string $prefix The prefix of the keys to check.
     * @param callable $listener Optional. The callback function to listen to active keys changes.
     * @return array An array describing the state of active keys.
     */
    public function getActiveKeys(string $prefix = '', callable $listener = null)
    {
        return $this->store->createSubscribable('getActiveKeys', $prefix, $listener);
    }

    /**
     * Get the value of a specified key.
     *
     * @param string $key The key of the value to retrieve.
     * @param callable $listener Optional. The callback function to listen to the specified key changes.
     * @return mixed A value of the specified key.
     */
    public function get(string $key = '', callable $listener = null)
    {
        return $this->store->createSubscribable('getValue', $key, $listener);
    }

    /**
     * Confirm that the consumer has successfully received and applied values, making them eligible for inclusion in
     * optimization statistics.
     */
    public function confirm()
    {
        waitFor(EFFECTIVE_GENOME_UPDATED, function() {
            $allocations = $this->context->get('experiments.allocations');
            if (!isset($allocations) || !count($allocations)) {
                return;
            }

            $entryPointEids = $this->store->activeEntryPoints();
            if (!count($entryPointEids)) {
                return;
            }

            $confirmations = $this->context->get('experiments.confirmations') ?? [];
            $confirmedCids = array_map(function($item) { return $item['cid']; }, $confirmations);

            $contaminations = $this->context->get('experiments.contaminations') ?? [];
            $contaminatedCids = array_map(function($item) { return $item['cid']; }, $contaminations);

            $confirmableAllocations = array_filter($allocations, function($alloc) use ($confirmedCids, $contaminatedCids, $entryPointEids) {
                return !in_array($alloc['cid'], $confirmedCids) &&
                    !in_array($alloc['cid'], $contaminatedCids) &&
                    in_array($alloc['eid'], $this->store->activeEids) &&
                    in_array($alloc['eid'], $entryPointEids);
            });
            if (!count($confirmableAllocations)) {
                return;
            }

            $timestamp = time();

            $contextConfirmations = array_map(function($alloc) use ($timestamp) {
                return [
                    'cid' => $alloc['cid'],
                    'timestamp' => $timestamp
                ];
            }, $confirmableAllocations);

            $newConfirmations = array_merge($contextConfirmations, $confirmations);
            $this->context->update(['experiments' => ['confirmations' => $newConfirmations]]);

            foreach ($confirmableAllocations as $alloc) {
                $this->eventBeacon->emit('confirmation', [
                    'uid' => $alloc['uid'],
                    'eid' => $alloc['eid'],
                    'cid' => $alloc['cid']
                ]);
            };

            $this->eventBeacon->flush();
            emit(EvolvClient::CONFIRMED);
        });
    }

    /**
     * Marks a consumer as unsuccessfully retrieving and / or applying requested values, making them ineligible for
     * inclusion in optimization statistics.
     *
     * @param array $details Optional. Information on the reason for contamination. If provided, the object should
     * contain a reason. Optionally, a 'details' value should be included for extra debugging info
     * @param bool $allExperiments If true, the user will be excluded from all optimizations, including optimization
     * not applicable to this page
     */
    public function contaminate(array $details, bool $allExperiments = false)
    {
        $allocations = $this->context->get('experiments.allocations');
        if (!isset($allocations) || !count($allocations)) {
          return;
        }

        if (!isset($details['reason'])) {
            throw new \Exception('Evolv: contamination details must include a reason');
        }

        $contaminations = $this->context->get('experiments.contaminations') ?? [];
        $contaminatedCids = array_map(function($item) { return $item['cid']; }, $contaminations);

        $contaminatableAllocations = array_filter($allocations, function($alloc) use ($contaminatedCids, $allExperiments) {
            return !in_array($alloc['cid'], $contaminatedCids) &&
                ($allExperiments || in_array($alloc['eid'], $this->store->activeEids));
        });
        if (!count($contaminatableAllocations)) {
            return;
        }

        $timestamp = time();

        $contextContaminations = array_map(function($alloc) use ($timestamp) {
            return [
                'cid' => $alloc['cid'],
                'timestamp' => $timestamp
            ];
        }, $contaminatableAllocations);

        $newContaminations = array_merge($contextContaminations, $contaminations);
        $this->context->update(['experiments' => ['contaminations' => $newContaminations]]);

        foreach ($contaminatableAllocations as $alloc) {
            $this->eventBeacon->emit('contamination', [
                'uid' => $alloc['uid'],
                'eid' => $alloc['eid'],
                'cid' => $alloc['cid'],
                'contaminationReason' => $details
            ]);
        };
    
        $this->eventBeacon->flush();
        emit(EvolvClient::CONTAMINATED);
    }
}

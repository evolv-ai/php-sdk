<?php

declare (strict_types=1);

use  App\EvolvClient\EvolvClient;
use  App\EvolvStore\Store;
use  App\EvolvContext\Context;
require_once  __DIR__ . '/App/EvolvClient.php';

require  'vendor/autoload.php';


//options: Partial<{analytics: boolean, bufferEvents?: boolean, environment: string, endpoint: string, auth?: any, clientName?: string, autoConfirm: boolean, context?: any, store?: any, version: number, hooks: RequestHooks}>
$json = '{"analytics": false, "environment": "cf95dc8c59", "endpoint": "https://participants-stg.evolv.ai/v1", "auth": [{"id" : "12w33", "secret" : "23eeee"}], "clientName": "asset-manager", "version": ""}';
//$array=json_decode($json);

$client = new EvolvClient($json);

$store  = new Store($json);

$cntx = new Context();

echo $cntx->initialize(6, 6,  $json, $json);
echo $cntx->ensureInitialized();

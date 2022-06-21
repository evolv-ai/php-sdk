<?php

declare (strict_types=1);

use  App\EvolvClient\EvolvClient;
use  App\EvolvContext\Context;

require_once  __DIR__ . '/App/EvolvClient.php';
require_once __DIR__ . '/App/EvolvContext.php';

require  'vendor/autoload.php';


$json = '{"analytics": "false", "bufferEvents": "false", "environment": "cf95dc8c59", "endpoint": "https://participants-stg.evolv.ai/v1", "auth": [{"id" : "12w33", "secret" : "23eeee"}],"uid": "54120622_1654686958544", "clientName": "asset-manager", "version": ""}';

//$client = new EvolvClient($json);

//$remoteContext = [];
//$localContext = [];

//$client->initialize($json,54120622_1654686958544,$remoteContext, $localContext);
//print_r($client);

//$keys = $client->getActiveKeys();





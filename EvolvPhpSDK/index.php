<?php

declare (strict_types=1);

use  App\EvolvClient\EvolvClient;

require_once  __DIR__ . '/App/EvolvClient.php';

require  'vendor/autoload.php';

$json = '{"analytics": "false", "bufferEvents": "false", "environment": "cf95dc8c59", "endpoint": "https://participants-stg.evolv.ai/v1", "auth": [{"id" : "12w33", "secret" : "23eeee"}],"uid": "54120622_1654686958544", "clientName": "asset-manager", "version": ""}';

$client = new EvolvClient($json);

$remoteContext = [];
$localContext = [];

$client->initialize($json,54120622_1654686958544,$remoteContext, $localContext);

//$client->set("web.url","http://fgh",true);

$client->remoteContext;

$set = $client->set("web.url","http://fgh",true);

$set2 = $client->set("age","234",true);

//print_r($set);

//print_r($set2);

//print_r($client);

//$key = $client->getActiveKeys();

//$client->print_r($key);

//print_r($key);

//var_dump($key);

//echo "<pre>";
//print_r($key);
//echo "<pre>";




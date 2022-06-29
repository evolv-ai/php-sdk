<?php

declare (strict_types=1);

use  App\EvolvClient\EvolvClient;

require_once  __DIR__ . '/App/EvolvClient.php';

require  'vendor/autoload.php';

$json = '{"environment": "758012fca1", "endpoint": "https://participants.evolv.ai/v1", "auth": [{"id" : "12w33", "secret" : "23eeee"}],"uid": "user_id", "clientName": "asset-manager", "version": ""}';

$client = new EvolvClient($json);

$remoteContext = [];

$localContext = [];

$client->initialize($json,'user_id',$remoteContext, $localContext);

$client->set("native.newUser",true,true);
$client->set("native.pageCategory",'home',true);
$client->set("native.pageCategory",'pdp',true);
//$client->set("extra_key",'not_exists',true);
//all active


$key = $client->getActiveKeys();

$client->print_r($key);

//$client->print_r($client->localContext());

//$client->print_r($client->remoteContext());



<?php

declare (strict_types=1);

use  App\EvolvClient\EvolvClient;

require  __DIR__ . '/App/EvolvClient.php';
require  'vendor/autoload.php';

$json = '{environment: "cf95dc8c59", endpoint: "https://participants-stg.evolv.ai/v1", clientName: "asset-manager", version: 1}';

$client = new EvolvClient($json);

//$client->buildOptions($json);


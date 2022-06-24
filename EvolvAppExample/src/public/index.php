<?php

/**
 * Laravel - A PHP Framework For Web Artisans
 *
 * @package  Laravel
 * @author   Taylor Otwell <taylor@laravel.com>
 */

define('LARAVEL_START', microtime(true));

require __DIR__.'/EvolvPhpSDK/App/EvolvClient.php';

use  App\EvolvClient\EvolvClient;


require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| our application. We just need to utilize it! We'll simply require it
| into the script here so that we don't have to worry about manual
| loading any of our classes later on. It feels great to relax.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Turn On The Lights
|--------------------------------------------------------------------------
|
| We need to illuminate PHP development, so let us turn on the lights.
| This bootstraps the framework and gets it ready for use, then it
| will load up this application so that we can run it and send
| the responses back to the browser and delight our users.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request
| through the kernel, and send the associated response back to
| the client's browser allowing them to enjoy the creative
| and wonderful application we have prepared for them.
|
*/

$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$response = $kernel->handle(
    $request = Illuminate\Http\Request::capture()
);

$response->send();

$kernel->terminate($request, $response);




$json = '{"analytics": "false", "bufferEvents": "false", "environment": "cf95dc8c59", "endpoint": "https://participants-stg.evolv.ai/v1", "auth": [{"id" : "12w33", "secret" : "23eeee"}],"uid": "54120622_1654686958544", "clientName": "asset-manager", "version": ""}';

$client = new EvolvClient($json);

$client->getActiveKey();


$client->initialize('54120622_1654686958544');

$client->initialize($json,'54120622_1654686958544',$remoteContext, $localContext);

<h1>Evolv Php Sdk</h1>

<h2>Install Php-Sdk through composer:</h2>

<code>https://packagist.org/packages/sdk-php/evolv-sdk</code>

<h2>Install</h2>

```php
  composer install
```
<h2>Start Eхample</h2>

```php
  composer start
```

<h2>Documentation Build</h2>

```php
  composer docs
```

<h2>Tests Build</h2>

```php
  composer test
```


```php
  <?php

  declare (strict_types=1);

  require_once __DIR__ . '/vendor/autoload.php';

  use Evolv\EvolvClient;
```

<h2>Client Initialization</h2>

```php
  <?php

  $client = new EvolvClient($environment, $endpoint);
  $client->initialize($uid);
```

<h2>About Evolv and the Ascend Product</h2>

Evolv Delivers Autonomous Optimization Across Web & Mobile.

You can find out more by visiting: <a href="https://www.evolv.ai/">https://www.evolv.ai/</a>

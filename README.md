evolv/php-sdk
=============

### Package Installation
- requires php >= 7.4.0

```php
  composer require evolv/php-sdk
```

### Package Usage

```php
  <?php

  use Evolv\EvolvClient;

  $client = new EvolvClient($environment, $endpoint);
  $client->initialize($uid);

```

### Available Development Scripts:

- install dependencies

```php
  composer install
```

- run the apache server with php 7.4.0

```php
  composer start
```
The example application will then be accessible on http://localhost:8000/Example/index.php
http://localhost:8000/Example/pdp.php

index.php has an example of controlling the text value of a button.
pdp.php has an example of selecting a different code path based on the variant's value.

- generate documentation

```php
  composer docs
```

- run unit tests

```php
  composer test
```

<h2>About Evolv Product</h2>

Evolv Delivers Autonomous Optimization Across Web & Mobile.

You can find out more by visiting: <a href="https://www.evolv.ai/">https://www.evolv.ai/</a>

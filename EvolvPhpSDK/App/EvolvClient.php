<?php

namespace App\EvolvClient;

use  App\EvolvStore\Store;
use  App\EvolvContext\Context;
use  App\EvolvOptions\Options;
use  PHPUnit\Framework\TestCase;

require 'vendor/autoload.php';
require __DIR__ . '/EvolvStore.php';
require __DIR__ . '/EvolvContext.php';
require __DIR__ . '/EvolvOptions.php';


class EvolvClient
{
    public $initialized = false;
    public $options;
    public $store;
    public $obj;
    public $context;


    public function setOptions($options)
    {

      $this->options =  Options::buildOptions($options);

        return $options;
    }


    public function __construct($options)
    {
        $this->options =  Options::buildOptions($options);

        $this->store =  new Store($this->options);

        $this->context = new Context($this->store);

    }

}




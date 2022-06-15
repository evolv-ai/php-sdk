<?php

namespace App\EvolvClient;

use  App\EvolvStore\Store;
use  App\EvolvContext\Context;
use  App\EvolvOptions\Options;
use  PHPUnit\Framework\TestCase;

require 'vendor/autoload.php';
require_once __DIR__ . '/EvolvStore.php';
require_once __DIR__ . '/EvolvContext.php';
require_once __DIR__ . '/EvolvOptions.php';

ini_set('display_errors', 'on');

class EvolvClient
{
    public $initialized = false;
    public $options;
    public $store;
    public $obj;
    public $context;
    public $error;


    public function setOptions($options)
    {

        $this->options = Options::buildOptions($options);

        return $this->options ;
    }


    public function __construct($options)
    {
        $this->options = $this->setOptions($options);

        $this->store = new Store($this->options);

        //$this->context = new Context($this->store);

    }

    public function initialize(){

    }

}






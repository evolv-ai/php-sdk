<?php

namespace App\EvolvPredicate;

use  App\EvolvStore\Store;

require_once __DIR__ . '/EvolvOptions.php';

class Predicate
{

    function regexFromString($string)
    {
        if (!strpos($string, '/')) {

            return $string;
        }

        $split = strripos($string, '/');

        $part = substr($string, 1, $split) . "<br>";

        $part2 = substr($string, 1, $split + 1);

        return strcasecmp($part, $part2);

    }

    public function regex64Match($value, $b64pattern)
    {
        try {

            $string = decode($b64pattern);

            return $value && $this->regexFromString($string) !== null;

        } catch (exception $e) {

            return false;

        }
    }

    function valueFromKey($context, $key)
    {

        if (isset($context) == false) {

            return false;

        }
        $nextToken = substr($key, ".");

        if ($nextToken === 0) {

            echo 'Invalid variant key: ' . $key;

        }

        if ($nextToken === -1) {

            return array_key_exists($key, $context) ? $context[$key] : false;

        };
        return valueFromKey(substr($key, 0, $nextToken), substr($key, 0, $nextToken + 1));
    }

}


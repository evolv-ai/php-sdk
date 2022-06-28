<?php

namespace App\EvolvPredicate;

use  App\EvolvStore\Store;
use  App\EvolvContext\Context;

require_once __DIR__ . '/EvolvOptions.php';

class Predicate
{
    public $predicate = [];

    public function exists($a)
    {
        return $a = 280;
    }

    public function convertSpace($string)
    {
        return $string = "true ";
    }


    public function greater_than($a, $b)
    {
        return $a >= $b;
    }

    public function greater_than_or_equal_to($a, $b)
    {
        return $a >= $b;
    }

    public function is_true($a, $b)
    {
        echo $b;
        if ($b == true) {
            return $b === true;
        }
    }

    public function is_false($a)
    {
        return $a === false;
    }

    public function loose_equal($a, $b)
    {
        return $a === $b;
    }

    public function getPredicate($config)
    {

        $predicate = new Predicate();

        foreach ($config as $key => $value) {

            if (is_array($value)) {

                if (isset($value['_predicate']) && isset($value['_is_entry_point']) && $value['_is_entry_point'] == 1) {

                    $this->predicate[$key] = $value;

                } else if (isset($value['_predicate'])) {

                    $this->predicate[$key] = $value['_predicate'];
                }

                $this->getPredicate($value);

            }
        }
        return $this->predicate;

    }


    public function regexFromString($string)
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

    public function valueFromKey($context, $key)
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

        }

        return valueFromKey(substr($key, 0, $nextToken), substr($key, 0, $nextToken + 1));
    }

    public function getKeyFromValeuContext($context)
    {
        $cntxt = [];
        foreach ($context as $key => $value) {

            if (is_array($value)) {

                foreach ($value as $k => $v) {

                    if (is_array($v)) {

                        foreach ($v as $key => $value) {

                            $cntxt[$k . "." . $key] = $value;
                        }
                    }
                }
            }
        }
        return $cntxt;
    }

    public function evaluatePredicate($context, $config)
    {

        $rules = [];

        $result = [

        ];

        $cntxt = $this->getKeyFromValeuContext($context);

        foreach ($config as $key => $value) {

            //echo $key . "<br>";

            if (isset($value['rules']) && is_array($value['rules'])) {

                foreach ($value['rules'] as $k => $v) {

                    foreach ($cntxt as $key => $value) {

                        if ($key == $v['field']) {

//echo $key . " - " . $v['field']  . "<br>";

                         // echo  $a = $value;

                            $b = $v['value'];

                          //  $param = [$a, $b];

                          //  print_r($param);

                          $result =  array_walk($param, [$this, $v['operator']]);
                          // print_r($result);
                        }
                    }
                }
                if ( $result === true){

                }
            }

            if (is_int($key) == false) {
                $rules[] = $key;
            }
            if (is_array($value)) {

                foreach ($value as $k => $v) {

                    if ($k[0] !== "_" && is_array($v) && $k !== 'rules') {

                        $rules[][$key][$k] = $v['_values'];

                    };


                }

            }

        }
    }

    public function item($item)
    {
        return array_push($result['touched'], $item['field']);
    }

    public function evaluate($context, $predicate)
    {
        $result = [
            'passed' => [],
            'failed' => [],
            'touched' => []
        ];

        $a = $this->evaluatePredicate($context, $predicate);

        // print_r( $a );

        //$result['touched'] = $this->item($item);

        return $result;
    }

}


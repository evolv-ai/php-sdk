<?php

namespace App\EvolvPredicate;

use  App\EvolvStore\Store;
use  App\EvolvContext\Context;

require_once __DIR__ . '/EvolvOptions.php';

class Predicate
{
    public $predicate = [];

    public $param = [];

    public $a = 'a';

    public $b = 'b';

    public $res;

    public $result;

    public $activeKeys = [];

    public function exists($a)
    {
        $result = (!empty($a) && isset($a)) ? true : false;

        return $result;
    }

    public function convertSpace($string)
    {
        return $string = "true";
    }


    public function greater_than($a, $b)
    {
        $result = $a >= $b ? true : false;

        return   $result;
    }

    public function greater_than_or_equal_to($a, $b)
    {
        $result = $a >= $b ? true : false;

        return   $result;
    }

    public function is_true($a, $b)
    {
        $result = $a === true ? true : false;

        return $result;
    }

    public function is_false($a)
    {
        $result = $a === false ? true : false;

        return $result;
    }

    public function loose_equal($a, $b)
    {
        $result = $a === $b ? true : false;

        return $result;
    }


    function not_exists($a, $b)
    {

        $result = (!empty($a)) ? true : false;

        return $result;
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
                    } else {
                        $cntxt[$k] = $value;
                    }
                }
            }
        }
        return $cntxt;
    }


    public function evaluatePredicate($context, $config)
    {

        $activeKeys = [];

        $cntxt = $this->getKeyFromValeuContext($context);

        $keys = [];

        foreach ($config as $key => $value) {

            array_push($keys, $key);

            echo $key . "<br>";
            echo "<pre>";
           print_r($value);
            echo "</pre>";

            if (isset($value['rules']) && is_array($value['rules'])) {

                if (is_array($cntxt) || is_object($cntxt)) {

                    foreach ($cntxt as $keyC => $valueC) {

                        if ($keyC == $value['rules'][0]['field']) {

                            $a = is_array($valueC) ? is_array($valueC) : $valueC;

                            $b = $value['rules'][0]['value'];

                            $callback = $value['rules'][0]['operator'];

                            $this->result = call_user_func_array([$this, $callback], [$a, $b]);

                            if ($this->result == true) {

                                $current = current($keys);

                                $next = next($keys);

                                $next = next($keys);

                                $prev = prev($keys);

                                $end = end($keys);

                                if (isset($prev)  && $key !== 0  &&  $next == $key || !isset($next)){

                                    $activeKeys[] = $prev . "." . $key;

                                }
                                else if (isset($next) && isset($prev) && $key !== 0 &&  $next !== $key)  {

                                    $activeKeys[] = $current . "." . $key;

                                }
                                else {

                                    $activeKeys[] = $key;

                                }
                            }
                        }
                    }
                }
            }
            foreach ($value as $k => $val) {

                if ($this->res == 1) {

                    if ($k[0] !== "_" && is_array($val) && $k !== 'rules') {

                        $activeKeys[] = $key . "." . $k;

                    }

                }

                if (isset($val['rules']) && is_array($val['rules'])) {

                    if (is_array($cntxt) || is_object($cntxt)) {

                        foreach ($cntxt as $keyC => $valueC) {

                            if ($keyC == $val['rules'][0]['field']) {

                                $a = $valueC;

                                $b = $val['rules'][0]['value'];

                                $callback = $val['rules'][0]['operator'];

                                $this->res = call_user_func_array([$this, $callback], [$a, $b]);

                            }
                        }
                        if ($this->res == 1) {

                            $activeKeys[] = $key;
                        }

                    }

                }

            }
        }

        return $activeKeys;

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

        $active = $this->evaluatePredicate($context, $predicate);


        return $active;
    }

}


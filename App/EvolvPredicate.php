<?php

namespace App\EvolvPredicate;

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

    public $parentPredicate = false;

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

        return $result;
    }

    public function greater_than_or_equal_to($a, $b)
    {
        $result = $a >= $b ? true : false;

        return $result;
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

        $result = (empty($a)) ? true : false;

        return $result;
    }

    /*    public function getPredicate($config)
        {

            $predicate = new Predicate();

            foreach ($config as $key => $value) {

                if (is_array($value)) {

                    if (isset($value['_predicate']) && isset($value['_is_entry_point']) && $value['_is_entry_point'] == 1) {

                        $this->predicate[$key] = $value;

                    } else if (isset($value['_predicate'])) {

                        $this->predicate[$key] = $value['_predicate'];
                    }



                }
            }
            return $this->predicate;

        }*/


    public function regexFromString($string)
    {
        if (!strpos($string, '/')) {

            return $string;
        }

        $split = strripos($string, '/');

        $part = substr($string, 1, $split);

        $part2 = substr($string, 1, $split + 1);

        return strcasecmp($part, $part2);

    }

    public function regex64Match($a, $b)
    {

        $result = decode($a) === true ? true : false;

        return $result;

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

        return $this->valueFromKey(substr($key, 0, $nextToken), substr($key, 0, $nextToken + 1));
    }

    public function getKeyFromValeuContext($context)
    {
        $cntxt = [];

        if (isset($context) && is_array($context)) {

            foreach ($context as $key => $value) {

                if (is_array($value)) {

                    foreach ($value as $k => $v) {

                        if (is_array($v)) {

                            foreach ($v as $key => $value) {

                                $cntxt[$k . "." . $key] = $value;

                            }
                        } elseif (!is_array($v)) {
                            $cntxt[$k] = $v;
                        }

                    }
                }


            }
        }
        return $cntxt;
    }

    public function evaluatePredicate($context, $config)
    {

        $activeKeys = [];

        $keys = [];

        $cntxt = $this->getKeyFromValeuContext($context);


        foreach ($config as $key => $value) {
            array_push($keys, $key);
        }


        foreach ($config as $key => $value) {

            foreach ($value as $key => $value) {

                if (isset($value['_predicate'])) {

                    if (is_array($cntxt) || is_object($cntxt)) {

                        foreach ($cntxt as $keyC => $valueC) {

                            if ($keyC == $value['_predicate']['rules'][0]['field']) {

                                $a = is_array($valueC) ? is_array($valueC) : $valueC;

                                $b = $value['_predicate']['rules'][0]['value'];

                                $callback = $value['_predicate']['rules'][0]['operator'];

                                $this->result = call_user_func_array([$this, $callback], [$a, $b]);

                                if ($callback == "is_true") $this->parentPredicate = $this->result;

                                if ($this->result == true && $this->parentPredicate == true) {

                                    if ($key != 0) {
                                        $activeKeys[] = $key;
                                    }
                                }
                            }
                        }
                    }

                    foreach ($value as $key => $value) {

                        if (isset($value['_predicate'])) {


                            if (is_array($cntxt) || is_object($cntxt)) {

                                foreach ($cntxt as $keyC => $valueC) {

                                    if ($keyC == $value['_predicate']['rules'][0]['field']) {

                                        $a = is_array($valueC) ? is_array($valueC) : $valueC;

                                        $b = $value['_predicate']['rules'][0]['value'];

                                        $callback = $value['_predicate']['rules'][0]['operator'];

                                        $this->res = call_user_func_array([$this, $callback], [$a, $b]);

                                        if ($this->res == true && $this->parentPredicate == true) {

                                            // if ($key != 0) {
                                            $activeKeys[] = $key;
                                            //  }
                                        }
                                    }
                                }
                            }
                        }
                        if (isset($value['_predicate']) && $value['_predicate']['combinator'] == "and") {
                            foreach ($value as $k => $val) {

                                if ($this->parentPredicate == 1 && $this->res == 1) {

                                    if ($k[0] !== "_" && is_array($val) && $k !== 'rules' && !isset($val['_predicate']['rules'])) {

                                        $activeKeys[] = $key . "." . $k;

                                    } else if (is_array($val) && isset($val['_predicate']['rules']) && $this->res == 1) {

                                        foreach ($cntxt as $keyC => $valueC) {

                                            if ($keyC == 'extra_key') {

                                                $a = is_array($valueC) ? is_array($valueC) : $valueC;

                                                $b = $val['_predicate']['rules'][0]['value'];

                                                $callback = $val['_predicate']['rules'][0]['operator'];

                                                $this->res = call_user_func_array([$this, $callback], [$a, $b]);

                                                if ($this->res == true && $this->parentPredicate == true) {

                                                    // if ($key != 0) {
                                                    $activeKeys[] = $key . "." . $k;
                                                    //  }
                                                }
                                            } else if ($keyC !== 'extra_key') {
                                                $a = $val['_predicate']['rules'][0]['value'];

                                                $callback = $val['_predicate']['rules'][0]['operator'];

                                                $this->res = call_user_func_array([$this, $callback], [$a, $b]);
                                            }
                                        }

                                        if ($this->res == true && $this->parentPredicate == true) {
                                            $activeKeys[] = $key . "." . $k;

                                        }
                                    }

                                }

                            }
                        }
                    }
                }

            }

        }

        return $activeKeys;

    }

    public
    function item($item)
    {
        return array_push($result['touched'], $item['field']);
    }

    public
    function evaluate($context, $predicate)
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


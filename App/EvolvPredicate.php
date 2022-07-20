<?php

namespace App\EvolvPredicate;

require_once __DIR__ . '/EvolvOptions.php';

class Predicate
{
    private $result;

    private $activeKeys = [];

    private $parentPredicate = false;

    private $extra_key = false;

    private $keys = [];

    private $filter;

    public function __construct()
    {

        $this->filter = [
            'exists' => function ($a, $b) {
                return $result = (!empty($a) && isset($a)) ? true : false;
            },
            'is_true' => function ($a, $b) {
                return $a === true ? true : false;
            },
            'contains' => function ($a, $b) {
                return $result = in_array($a, $b);
            },
            'defined' => function ($a, $b) {
                return $result = (isset($a) && !empty($a)) ? true : false;
            },
            'equal' => function ($a, $b) {
                return $a === $b;
            },
            'convertSpace' => function ($string) {
                return $string = "true";
            },
            'greater_than' => function ($a, $b) {
                return $result = ($a > $b) ? true : false;
            },
            'greater_than_or_equal_to' => function ($a, $b) {
                return $result = ($a >= $b) ? true : false;
            },
            'is_true' => function ($a, $b) {
                return $result = $a === true ? true : false;
            },
            'is_false' => function ($a, $b) {
                return $result = $a === false ? true : false;
            },
            'loose_equal' => function ($a, $b) {
                return $result = $a === $b ? true : false;
            },
            'not_contains' => function ($a, $b) {
                return $result = !in_array($a, $b);
            },
            'not_exists' => function ($a, $b) {
                return $result = (empty($a)) ? true : false;
            },
            'not_defined' => function ($a, $b) {
                return $result = (isset($a) == false && empty($a)) ? true : false;
            },
            'testNotRegexMatch' => function ($value, $pattern) {
                return $result = !preg_match($value, $pattern, $matches);
            },
            'not_equal' => function ($a, $b) {
                return $result = ($a !== $b) ? true : false;
            },
            'not_starts_with' => function ($a, $b) {
                return str_starts_with($a, $b);
            },
            'starts_with' => function ($a, $b) {
                return str_starts_with($a, $b);
            },
            'less_than' => function ($a, $b) {
                return $a < $b;
            },
            'less_than_or_equal_to' => function ($a, $b) {
                return $a <= $b;
            },
            'loose_not_equal' => function ($a, $b) {
                return $a != $b;
            },
            'kv_equal' => function ($obj, $params) {
                return $obj[$params[0]] === $params[1];
            },
            'kv_not_equal' => function ($obj, $params) {
                return $obj[$params[0]] !== $params[1];
            },
            'regex64_match' => function ($a, $b) {

                $string = base64_decode($b);

                $string = $this->regexFromString($string);

                $result = strcasecmp($a, $string) == 0 ? true : false;

                return $result;
            }

        ];
    }


    private function regexFromString($string)
    {
        if (!str_starts_with($string, '/')) {

            return $string;
        }

        $split = strripos($string, '/');

        $part = substr($string, 1, $split);

        $part2 = substr($string, 1, $split + 1);

        return $part2;

    }

    private function kv_equal($array, $param)
    {

        return $array[0] === $param[1];

    }


    private function getContextKey($cntxt, $key, $field, $callback, $b)
    {

        foreach ($cntxt as $keyC => $valueC) {
//print_r($b);
            $this->extra_key = $keyC == "extra_key" ? true : false;

            if ($keyC == $field && $this->extra_key == false) {
//echo $keyC . ' == ' .$field;
                $a = is_array($valueC) ? is_array($valueC) : $valueC;

                $result = $this->filter[$callback]($a, $b);
                // $result = call_user_func_array([$this, $callback], [$a, $b]);

                if ($callback == "is_true" && $result === true) $this->parentPredicate = true;

                if ($result == true && $this->parentPredicate == true) {

                    if ($key !== 0) {
                        $this->activeKeys[] = $key;
                    }
                }
            }
        }
    }

    public function getKeyFromValeuContext($context)
    {
        $cntxt = [];

        if (isset($context) && is_array($context)) {

            foreach ($context as $key => $value) {

                if (is_array($value)) {

                    foreach ($value as $k => $v) {

                        $cntxt += [$key . "." . $k => $v];

                    }
                }


            }
        }

        return $cntxt;
    }

    public function evaluatePredicate($context, $config)
    {

        $cntxt = $this->getKeyFromValeuContext($context);

        if (is_array($config) || is_object($config)) {

            foreach ($config as $key => $value) {

                if (is_array($value) && isset($value["_predicate"])) {
                    //  print_r($value);
                    if (is_array($cntxt) || is_object($cntxt)) {

                        $field = $value['_predicate']['rules'][0]['field'];

                        $b = $value['_predicate']['rules'][0]['value'];
//echo $b;
                        $callback = $value['_predicate']['rules'][0]['operator'];
//echo $callback;
                        $combinator = $value['_predicate']['combinator'];

                        foreach ($cntxt as $keyC => $valueC) {

                            $this->extra_key = $keyC == "extra_key" ? true : false;

                            if ($keyC == $field && $this->extra_key == false) {

                                $a = is_array($valueC) ? is_array($valueC) : $valueC;

                                $result = $this->filter[$callback]($a, $b);

                                //$result = call_user_func_array([$this, $callback], [$a, $b]);

                                if ($callback == "is_true" && $result === true) $this->parentPredicate = true;

                                if ($result == true && $this->parentPredicate == true) {

                                    if ($key !== 0) {

                                        $this->activeKeys[] = $key;
                                    }
                                }
                            }
                        }

                        if ($combinator == "and" && isset($result) && $result == true && $this->parentPredicate == true) {

                            foreach ($value as $k => $val) {

                                if ($k[0] !== "_" && is_array($val) && count($val) > 0 && !isset($val['_predicate'])) {

                                    $this->activeKeys[] = $key . "." . $k;

                                } else if (isset($val['_predicate'])) {

                                    $field = $val['_predicate']['rules'][0]['field'];

                                    $b = $val['_predicate']['rules'][0]['value'];
                                    //print_r($b);

                                    $callback = $val['_predicate']['rules'][0]['operator'];

                                    if ($field == 'extra_key') {

                                        $this->activeKeys[] = $key . "." . $k;
                                    } else {

                                        if ($k == $field && $this->extra_key == false) {

                                            $this->getContextKey($cntxt, $k, $field, $callback, $b);

                                        }
                                    }

                                }
                            }

                        }

                    }

                }
                $this->evaluatePredicate($context, $value);
            }
        }
        // print_r($this->activeKeys);
        return $this->activeKeys;
    }

    public function getValue($prev, $next, $context)
    {
        if (is_array($context)) {

            foreach ($context as $k => $value) {

                if (is_array($value)) {

                    if ($k === $prev && is_array($context[$prev])) {

                        if (!empty($next)) {

                            $this->result = $context[$prev];

                            foreach ($context[$prev] as $key => $value) {

                                $this->result = $value;
                            }
                        } else {
                            $this->result = $value;
                        }

                    }
                    $this->getValue($prev, $next, $value);

                }
            }

        }
        return $this->result;
    }

    public function valueFromKey($key, $context)
    {

        if (isset($context) == false) {

            return false;

        }
        if (substr($key, 0, strpos($key, "."))) {

            $prevToken = substr($key, 0, strpos($key, "."));

        } else {

            $prevToken = $key;

        }

        $nextToken = substr(strrchr($key, "."), 1);

        if (empty($key)) {

            echo 'Invalid variant key: ' . $key;

        } else {

            return $this->getValue($prevToken, $nextToken, $context);

        }

    }

    public function valueFromKeyRevoluate($key, $context)
    {

        if (isset($context) == false) {

            return false;

        }
        if (substr($key, 0, strpos($key, "."))) {

            $prevToken = substr($key, 0, strpos($key, "."));

        } else {

            $prevToken = $key;

        }

        $nextToken = substr(strrchr($key, "."), 1);

        if (empty($key)) {

            echo 'Invalid variant key: ' . $key;

        } else {

            return $this->getValue($prevToken, $nextToken, $context);

        }

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


<?php

namespace App\EvolvOptions;

class  Options{

    public $obj;

    public static function Options($obj)
    {

        $obj = json_decode($obj, true);

        return $obj;
    }

    public static function buildOptions($obj){

        $obj = is_array($obj) ? json_decode($obj) : $obj;

       return $obj;

    }

}

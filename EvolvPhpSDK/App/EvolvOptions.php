<?php

namespace App\EvolvOptions;

class  Options{

    public static function Parse($obj)
    {

        $obj = json_decode($obj, true);

        return $obj;
    }

    public static function buildOptions($obj)
    {

        $obj = json_decode($obj);

        if (!array_key_exists('environment', $obj) || empty($obj->environment)):

            echo $error = '"environment" must be specified';

            exit();

        endif;

        if (!array_key_exists('autoConfirm', $obj)) {

            $obj->autoConfirm = true;

        }

        $obj->version = !empty($obj->version) ? $obj->version : 1;

        $obj->endpoint = !empty($obj->endpoint) ? $obj->endpoint : 'https://participants.evolv.ai/' . 'v' . $obj->version;

        $obj->analytics = array_key_exists('analytics', $obj) && empty(array_key_exists('analytics', $obj)) ? $obj->analytics : $obj->version = 1;

        $obj = json_encode($obj, JSON_UNESCAPED_SLASHES);

        //print_r($obj);

        return $obj;
    }


}

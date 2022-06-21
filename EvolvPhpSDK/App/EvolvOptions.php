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

        if (!property_exists($obj,'environment') || empty($obj->environment)):

            echo $error = '"environment" must be specified';

            exit();

        endif;

        if (!property_exists($obj,'autoConfirm')) {

            $obj->autoConfirm = true;

        }

        $obj->version = !empty($obj->version) ? $obj->version : 1;

        $obj->endpoint = !empty($obj->endpoint) ? $obj->endpoint : 'https://participants.evolv.ai/' . 'v' . $obj->version;

        $obj->analytics = property_exists($obj,'analytics') && empty(property_exists($obj,'analytics' )) ? $obj->analytics : $obj->version = 1;

        $obj = json_encode($obj, JSON_UNESCAPED_SLASHES);

        //print_r($obj);

        return $obj;
    }


}

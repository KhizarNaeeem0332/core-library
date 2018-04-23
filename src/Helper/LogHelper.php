<?php

namespace Bindeveloperz\Core\Helper;


class LogHelper
{

    public static function d($msg , $title= "")
    {
        echo "<pre>";
        echo "<span>$title</span> ";
        print_r($msg);
        echo "</pre>";
    }


    public static function ddd($msg, $title= "")
    {
        echo "<pre>";
        self::d($msg , $title);
        die();
    }

    /*
    * Dumps a value using print_r inside a <pre> tag.
    *
    * @param mixed $var     Variable to dump
    * @param mixed $var,... Unlimited optional variables to dump
    */

    public static function dump($var)
    {
        foreach (func_get_args() as $var) {
            echo '<pre>';
            print_r($var);
            echo '</pre>';
        }
    }


}
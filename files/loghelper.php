<?php


use Bindeveloperz\Core\Helper\LogHelper;


if(!function_exists("d"))
{
    function d($data , $title='')
    {
        LogHelper::d($data , $title);
    }
}

if(!function_exists("ddd"))
{
    function ddd($data , $title='')
    {
        LogHelper::ddd($data , $title);
    }
}


if(!function_exists("dump"))
{
    function dump($args)
    {
        LogHelper::dump($args);
    }
}
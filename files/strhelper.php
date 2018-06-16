<?php


use Bindeveloperz\Core\Helper\StrHelper;

if(!function_exists("lower"))
{
    function lower($str)
    {
        return StrHelper::lower($str);
    }
}

if(!function_exists("upper"))
{
    function upper($str)
    {
        return strtoupper($str);
    }
}

if(!function_exists("initCap"))
{
    function initCap($str)
    {
        return ucwords($str);
    }
}

if(!function_exists("uFirst"))
{
    function uFirst($str)
    {
        return ucfirst($str);
    }
}

if(!function_exists("nvl"))
{
    function nvl($value, $default = '')
    {
        return StrHelper::nvl($value, $default);
    }
}


if(!function_exists("isEmpty"))
{
    function isEmpty($value)
    {
        return StrHelper::isEmpty($value);
    }
}

if(!function_exists("checkMin"))
{
    function checkMin($num1, $num2, $eq = false)
    {
        return StrHelper::checkMin($num1, $num2, $eq);
    }
}


if(!function_exists("getMin"))
{
    function getMin($num1, $num2, $eq = false)
    {
        return StrHelper::getMin($num1, $num2, $eq);
    }
}






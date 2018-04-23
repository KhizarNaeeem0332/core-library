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
        return StrHelper::upper($str);
    }
}


if(!function_exists("initCap"))
{
    function initCap($str)
    {
        return StrHelper::initCap($str);
    }
}


if(!function_exists("uFirst"))
{
    function uFirst($str)
    {
        return StrHelper::uFirst($str);
    }
}



if(!function_exists("nvl"))
{
    function nvl($value, $ifnull = '')
    {
        return StrHelper::nvl($value, $ifnull);
    }
}







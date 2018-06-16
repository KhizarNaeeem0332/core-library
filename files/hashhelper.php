<?php


use Bindeveloperz\Core\Helper\HashHelper;

if(!function_exists("encryptData"))
{
    function encryptData($str, $salt = null)
    {
        return HashHelper::encryptData($str, $salt);
    }
}


if(!function_exists("decryptData"))
{
    function decryptData($str, $salt = null)
    {
        return HashHelper::decryptData($str, $salt);
    }
}










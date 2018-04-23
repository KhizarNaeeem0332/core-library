<?php

namespace Bindeveloperz\Core\Helper;


use RuntimeException;

class HashHelper
{

    public static function encryptData($str, $salt = null)
    {
        $salt = md5($salt);
        $out = '';
        for ($i = 0; $i < strlen($str); $i++) {
            $kc = substr($salt, ($i % strlen($salt)) - 1, 1);
            $out .= chr(ord($str{$i}) + ord($kc));
        }
        $out = base64_encode($out);
        $out = str_replace(array(
            '/',
            '=',
        ), array(
            '-',
            '',
        ), $out);
        return $out;
    }

    public static function decryptData($str, $salt = null)
    {
        $salt = md5($salt);
        $out = '';
        $str = str_replace(array(
            '-',
            ' ',
            '%20',
        ), array(
            '/',
            '+',
            '+',
        ), $str);
        $str = base64_decode($str);
        for ($i = 0; $i < strlen($str); $i++) {
            $kc = substr($salt, ($i % strlen($salt)) - 1, 1);
            $out .= chr(ord($str{$i}) - ord($kc));
        }
        return $out;
    }



    public static function base64($value)
    {
        return (string)preg_replace('#[^A-Z0-9\/+=]#i', '', $value);
    }


}
<?php

namespace Bindeveloperz\Core\Support;


class Hash
{

    public static function encrypt($string, $secretKey = null)
    {


        if($string == '')
        {
            return "";
        }


        $secret_key = $secretKey;
        $secret_iv = $secretKey;

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );
        $output = base64_encode( openssl_encrypt( $string, $encrypt_method, $key, 0, $iv ) );
        return $output;
    }


    public static function decrypt($string, $secretKey = null)
    {

        if($string == '')
        {
            return "";
        }

        $secret_key = $secretKey;
        $secret_iv = $secretKey;

        $output = false;
        $encrypt_method = "AES-256-CBC";
        $key = hash( 'sha256', $secret_key );
        $iv = substr( hash( 'sha256', $secret_iv ), 0, 16 );


        $output = openssl_decrypt( base64_decode( $string ), $encrypt_method, $key, 0, $iv );


        return $output;
    }



}
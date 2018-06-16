<?php

namespace Bindeveloperz\Core\Support;


class Session
{
    private static $sessionCreated = false ;

    public static function start()
    {
        if(!self::$sessionCreated)
        {
            session_start();
            self::$sessionCreated = true;
        }
    }
    public static function exists($name)
    {
        return (isset($_SESSION[$name])) ? true : false;
    }
    public static function set($key , $value)
    {
        $_SESSION[$key] = $value;
    }
    public static function get($key , $otherKey = false)
    {
        if($otherKey)
        {
            return isset($_SESSION[$key][$otherKey]) ? $_SESSION[$key][$otherKey] : false;
        }
        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }
    public static function destroy($name)
    {
        if(self::exists($name))
        {
            unset($_SESSION[$name]);
            session_destroy();
        }
    }
    public static function destroyAll()
    {
        session_unset();
        session_destroy();
    }


    public static function flash($name , $string = '')
    {
        if(self::exists($name))
        {
            $session = self::get($name);
            self::delete($name);
            return $session ;
        }
        else
        {
            self::set($name  , $string);
        }
    }
    public static function log($type = 'print_r')
    {
        echo "<pre>";
        if($type == "var_dump")
        {
            var_dump($_SESSION);
        }
        else
        {
            print_r($_SESSION);
        }

        echo "</pre>";
    }

}
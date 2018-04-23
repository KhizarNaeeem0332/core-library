<?php


namespace Bindeveloperz\Core\Filesystem;


use Illuminate\Filesystem\Filesystem;

class File
{

    public static function getInstance()
    {
        return new FileSystem();
    }


}
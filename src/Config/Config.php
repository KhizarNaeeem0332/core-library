<?php


namespace Bindeveloperz\Core\Config;

use Illuminate\Config\Repository;
use Illuminate\Filesystem\Filesystem;
use Symfony\Component\Finder\Finder;

class Config
{


	public static function getInstance($configPath)
    {
    	//require 'vendor/illuminate/support/Illuminate/Support/helpers.php';

        $files = [];
        $phpFiles = Finder::create()->files()->name('*.php')->in($configPath)->depth(0);
        foreach ($phpFiles as $file) {
            $files[basename($file->getRealPath(), '.php')] = require $file->getRealPath();
        }

		return new Repository($files);


    }

}
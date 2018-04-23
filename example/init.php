<?php


use Bindeveloperz\Core\Database\DB;
use Bindeveloperz\Core\Log\ErrorHandler;

require_once  '../vendor/autoload.php';

$config = require_once  'configs/database.php';


$db = DB::getInstance($config["database"]["mysql"]);
$schema = \Bindeveloperz\Core\Database\Schema::getInstance();

$file = Bindeveloperz\Core\Filesystem\File::getInstance();



if($config["app"]["debug"])
{
    $error  = new ErrorHandler();
    $error->run();
}


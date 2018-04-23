<?php

namespace Bindeveloperz\Core\Database;
use Illuminate\Database\Capsule\Manager as Capsule;

class DB
{
    public static function getInstance($config)
    {
        $capsule = new Capsule();
        $capsule->addConnection($config);
        $capsule->setAsGlobal();
        return $capsule;
    }
}
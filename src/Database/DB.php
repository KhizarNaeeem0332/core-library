<?php

namespace Bindeveloperz\Core\Database;
use Illuminate\Container\Container;
use Illuminate\Database\Capsule\Manager as Capsule;
use Illuminate\Events\Dispatcher;

class DB
{
    public static function getInstance($config)
    {
        $capsule = new Capsule();
        $capsule->addConnection($config);
        $capsule->setEventDispatcher(new Dispatcher(new Container));
        $capsule->setAsGlobal();
        $capsule->bootEloquent();
        return $capsule;
    }


    public function render($record)
    {
        Pagination::render($record);
    }





}
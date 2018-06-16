<?php

namespace Bindeveloperz\Core\Database;
use Illuminate\Database\Capsule\Manager as Capsule;

class Schema
{
    public static function getInstance()
    {
        return Capsule::schema();
    }

}
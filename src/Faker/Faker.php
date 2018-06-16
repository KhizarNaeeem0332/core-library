<?php

namespace Bindeveloperz\Core\Faker;


use Faker\Factory;

class Faker
{
    public static function getInstance($locale = 'en_US')
    {
        return Factory::create($locale);
    }
}
<?php

namespace Bindeveloperz\Core\Log;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;


class ErrorHandler
{

    private $whoops ;

    public function __construct()
    {
        $this->whoops = new \Whoops\Run;
    }


    public function run()
    {
        $this->whoops->pushHandler(new \Whoops\Handler\PrettyPageHandler);
        $this->whoops->register();
    }


}

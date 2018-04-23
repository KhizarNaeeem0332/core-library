<?php

namespace Bindeveloperz\Core\Validation;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;


class Validator
{

    private $factory ;

    public function __construct()
    {
        $loader = new FileLoader(new Filesystem,  'lang' );
        $translator = new Translator( $loader , 'en');
        $this->factory  =  new Factory($translator);
    }


    public function validate($data , $rules , $messages = [] ,  $customAttributes = [])
    {
        $result = $this->factory->make(
            $data,
            $rules ,
            $messages,
            $customAttributes
        );
        return $result->messages();
    }

    public function getValidator()
    {
        return $this->factory;
    }

}
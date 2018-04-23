<?php

namespace Bindeveloperz\Core\Validation;

use Illuminate\Database\DatabaseManager;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\DatabasePresenceVerifier;
use Illuminate\Validation\Factory;
use Illuminate\Validation\PresenceVerifierInterface;


class Validator
{

    private $factory ;
    private $loader ;
    private $translator ;
    public function __construct( $db = null)
    {

        $this->loader = new FileLoader(new Filesystem,  'lang' );
        $this->translator = new Translator( $this->loader , 'en');
        $this->factory  =  new Factory($this->translator);

        if($db != null)
        {
            $presence = new DatabasePresenceVerifier($db->getDatabaseManager());
            $this->factory->setPresenceVerifier($presence);
        }
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


    public function setConnection($db)
    {
        $presence = new DatabasePresenceVerifier($db);

    }


}

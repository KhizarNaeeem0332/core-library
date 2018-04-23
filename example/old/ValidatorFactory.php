<?php

namespace Bindeveloperz\Core\Validation ;



use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;
use Illuminate\Validation\Factory;

abstract class ValidatorFactory
{

    protected $factory;


    public function __construct()
    {
        $translation_file_loader = new FileLoader(new Filesystem, DIRECTORY_SEPARATOR . 'Lang');



        $translator = new Translator($translation_file_loader, 'en');
        $this->factory = new Factory($translator);
    }













    /*    public function __construct()
        {
            $translator = $this->setupTranslator();
            $this->factory = new Factory($translator);
        }*/

/*    protected function setupTranslator()
    {
        $translator = new Translator(\Illuminate\Contracts\Translation\Loader::class, new MessageSelector);
        $translator->addLoader('array', new ArrayLoader());
        $translator->addResource('array', [
            'validation' => [
                'accepted' 				=> 'The :attribute must be accepted.',
                'active_url' 			=> 'The :attribute is not a valid URL.',
                'after' 				=> 'The :attribute must be a date after :date.',
                'alpha' 				=> 'The :attribute may only contain letters.',
                'alpha_dash' 			=> 'The :attribute may only contain letters, numbers, and dashes.',
                'alpha_num' 			=> 'The :attribute may only contain letters and numbers.',
                'array' 				=> 'The :attribute must be an array.',
                'before' 				=> 'The :attribute must be a date before :date.',
                'between' 				=> [
                    'numeric' 			=> 'The :attribute must be between :min and :max.',
                    'file' 				=> 'The :attribute must be between :min and :max kilobytes.',
                    'string' 			=> 'The :attribute must be between :min and :max characters.',
                    'array' 			=> 'The :attribute must have between :min and :max items.',
                ],
                'boolean' 				=> 'The :attribute field must be true or false.',
                'confirmed' 			=> 'The :attribute confirmation does not match.',
                'date' 					=> 'The :attribute is not a valid date.',
                'date_format' 			=> 'The :attribute does not match the format :format.',
                'different' 			=> 'The :attribute and :other must be different.',
                'digits' 				=> 'The :attribute must be :digits digits.',
                'digits_between' 		=> 'The :attribute must be between :min and :max digits.',
                'email' 				=> 'The :attribute must be a valid email address.',
                'exists' 				=> 'The selected :attribute is invalid.',
                'filled' 				=> 'The :attribute field is required.',
                'image' 				=> 'The :attribute must be an image.',
                'in' 					=> 'The selected :attribute is invalid.',
                'integer' 				=> 'The :attribute must be an integer.',
                'ip' 					=> 'The :attribute must be a valid IP address.',
                'json' 					=> 'The :attribute must be a valid JSON string.',
                'max' 					=> [
                    'numeric' 			=> 'The :attribute may not be greater than :max.',
                    'file' 				=> 'The :attribute may not be greater than :max kilobytes.',
                    'string' 			=> 'The :attribute may not be greater than :max characters.',
                    'array' 			=> 'The :attribute may not have more than :max items.',
                ],
                'mimes' 				=> 'The :attribute must be a file of type: :values.',
                'min' 					=> [
                    'numeric' 			=> 'The :attribute must be at least :min.',
                    'file' 				=> 'The :attribute must be at least :min kilobytes.',
                    'string' 			=> 'The :attribute must be at least :min characters.',
                    'array' 			=> 'The :attribute must have at least :min items.',
                ],
                'not_in' 				=> 'The selected :attribute is invalid.',
                'numeric' 				=> 'The :attribute must be a number.',
                'present' 				=> 'The :attribute field must be present.',
                'regex' 				=> 'The :attribute format is invalid.',
                'required' 				=> 'The :attribute field is required.',
                'required_if' 			=> 'The :attribute field is required when :other is :value.',
                'required_unless' 		=> 'The :attribute field is required unless :other is in :values.',
                'required_with' 		=> 'The :attribute field is required when :values is present.',
                'required_with_all' 	=> 'The :attribute field is required when :values is present.',
                'required_without' 		=> 'The :attribute field is required when :values is not present.',
                'required_without_all' 	=> 'The :attribute field is required when none of :values are present.',
                'same' 					=> 'The :attribute and :other must match.',
                'size' 					=> [
                    'numeric' 			=> 'The :attribute must be :size.',
                    'file' 				=> 'The :attribute must be :size kilobytes.',
                    'string' 			=> 'The :attribute must be :size characters.',
                    'array' 			=> 'The :attribute must contain :size items.',
                ],
                'string' 				=> 'The :attribute must be a string.',
                'timezone' 				=> 'The :attribute must be a valid zone.',
                'unique' 				=> 'The :attribute has already been taken.',
                'url' 					=> 'The :attribute format is invalid.'
            ]
        ], 'en');

        return $translator;
    }

    public function __call($method, $args)
    {
        return call_user_func_array([$this->factory, $method], $args);
    }*/

}
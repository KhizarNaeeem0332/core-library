<?php

require_once 'init.php';


$validate = new \Bindeveloperz\Core\Validation\Validator();





$errors = $validate->validate(["name" => "qwe123" , "age" => 12] , ["name" => "required"] , ["name.required" => "name is required"]);



if($errors->isNotEmpty())
{
    foreach($errors->all() as $error)
    {
        echo $error  . '<br>';
    }
}
else
{
    echo "all is well";
}



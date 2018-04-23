<?php

require_once 'init.php';


$validate = new \Bindeveloperz\Core\Validation\Validator($db);





$errors = $validate->validate(["username" => "master" , "age" => 12] , ["username" => "unique:users"] , ["username.unique" => "name is required"]);



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

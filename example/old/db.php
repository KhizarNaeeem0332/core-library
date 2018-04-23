<?php


use Bindeveloperz\Core\Database\DB;
use Bindeveloperz\Core\Database\Schema;
use Bindeveloperz\Core\Filesystem\File;
use Bindeveloperz\Core\Validation\Validator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;
use Illuminate\Translation\Translator;

require_once '../core/vendor/autoload.php';




$config = [

    'sqlite' => [
        'driver' => 'sqlite',
        'database' => 'path',
        'prefix' => '',
    ],

    'mysql' => [
        'driver' => 'mysql',
        'host' => 'localhost',
        'port' => '3306',
        'database' => 'ipaperbank',
        'username' => 'root',
        'password' => '',
        'unix_socket' => '',
        'charset' => 'utf8',
        'collation' => 'utf8_general_ci',
        'prefix' => '',
        'strict' => true,
        'engine' => null,
    ],

    'pgsql' => [
        'driver' => 'pgsql',
        'host' => '127.0.0.1',
        'port' =>  '5432',
        'database' => 'forge',
        'username' => 'forge',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
        'schema' => 'public',
        'sslmode' => 'prefer',
    ],

    'sqlsrv' => [
        'driver' => 'sqlsrv',
        'host' => 'localhost',
        'port' => '1433',
        'database' => 'forge',
        'username' => 'forge',
        'password' => '',
        'charset' => 'utf8',
        'prefix' => '',
    ],
];



$db = DB::getInstance($config["mysql"]);
$schema = Schema::getInstance();
$file = File::getInstance();
$validate = new Validator();



$books = $db->table("books_defining")->toSql();


dd($books);



/*
$messages = ["name.required" => "name is required" ,
    "age.required" => "age is required",
    "age.integer" => "age must be number ",
];

$errors = $validation->validate(
    ["name" => "" , "age" => "asdf"] ,
    ["name" => "required" , "age" => "required|integer"],
    $messages
);

dd($errors->get('age'));

*/





function dd($data)
{
    echo "<pre>";
    print_r($data);
    echo "</pre>";
}



//
//
//$translation_file_loader = new FileLoader(new Filesystem, DIRECTORY_SEPARATOR . 'Lang');
//$translator = new Translator($translation_file_loader, 'en');
//$validation_factory = new Illuminate\Validation\Factory($translator);
//
//
//var_dump($validation_factory);
//


/*$db->table("users")->insert([""]);


$schema->create("links" , function($table){
    $table->increments('id');
    $table->string('name');
    $table->timestamps();
});*/


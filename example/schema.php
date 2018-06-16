<?php

require_once 'init.php';



$_schema->create("khizar" , function (Illuminate\Database\Schema\Blueprint $table){
    $table->increments("id");
    $table->string("name")->unique();
});

$_schema->drop("khizar");
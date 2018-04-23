<?php

require_once 'init.php';



$schema->create("khizar" , function (Illuminate\Database\Schema\Blueprint $table){
    $table->increments("id");
    $table->string("name")->unique();
});

$schema->drop("khizar");
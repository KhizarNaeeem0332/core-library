<?php


use Bindeveloperz\Core\Models\Snippet;

require_once 'init.php';



$record = $_db::table("snippets")->get();

$record = Snippet::get();

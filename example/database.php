<?php

require_once 'init.php';


$users = $db->table("users")->get();


d($users);



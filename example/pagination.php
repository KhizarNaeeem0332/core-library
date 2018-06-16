<?php


require_once 'init.php';


$query = $_db->table("tblcountries");
$users = $_page->result($query)->paginate(25);


foreach($users as $user)
{
    echo $user->short_name  . "<br>";
}


$_page->render();



//$array = [1,2,3,4,5,6,7,8,9,12,23,4,5,56,7];
//$users = $_page->result($array)->paginate(10);
//d($users);
//$_page->render();

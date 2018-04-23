<?php

require_once './support/vendor/autoload.php';



use ItechPk\Support\Validation\Validation;



$errors = new Validation();


if(isset($_POST["btnSave"]))
{

//
//

    $rules = [];
    $messages= [];
    foreach ($_POST["name"] as $key => $value)
    {
        $rules["name.$key"] = "required|min:4";
        $rules["age.$key"] = "required";
        $messages["name.$key.required"] = "Name is required at row $key";
        $messages["age.$key.required"] = "Age is required at row $key " ;
        $messages["name.$key.min"] = "Name must be greater than 4 at row $key";
    }

    $errors->multiValidate($_POST , $rules  ,$messages );

}


//dd($errors->all());

//var_dump($errors->all());

?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Validation</title>
</head>
<body>


<div>
    <?php

    if($errors->any())
    {
        foreach($errors->errors() as $error)
        {
           echo "<li>$error</li>";
        }
    }

    ?>
</div>

<form action="" method="post">

    <?php for ($i=0 ; $i <3; $i++) :?>
        <div>
            <input type="text" name="name[]" placeholder="name">
            <?php if($errors->has("name.$i")): ?>
                <span><?=$errors->get("name.$i")?></span>
            <?php endif; ?>

            <input type="text" name="age[]" placeholder="age">
            <?php if($errors->has("age.$i")): ?>
                <span><?=$errors->get("age.$i")?></span>
            <?php endif; ?>
        </div>
    <?php endfor;?>




    <div>
        <button type="submit" name="btnSave">Save</button>
    </div>


</form>


</body>
</html>










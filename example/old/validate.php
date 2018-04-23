<?php

require_once './support/vendor/autoload.php';



use ItechPk\Support\Validation\Validation;



$errors = new Validation();


if(isset($_POST["btnSave"]))
{

    $errors->validate($_POST , ["name" => "max:2" , "age" => "required"]);

}


dd($errors->errors());

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

        <div>
            <input type="text" name="name" placeholder="name">
            <?php if($errors->has("name")): ?>
                <span><?=$errors->get("name")?></span>
            <?php endif; ?>

            <input type="text" name="age" placeholder="age">
            <?php if($errors->has("age")): ?>
                <span><?=$errors->get("age")?></span>
            <?php endif; ?>
        </div>

    <div>
        <button type="submit" name="btnSave">Save</button>
    </div>


</form>


</body>
</html>










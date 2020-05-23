<?php

$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');
$id = (int)$_GET['id'];
$state = $connection->query("SELECT state_title, state_content, state_newTime, userName, 
surname, country, login, cat_title FROM states JOIN registrations USING (id_login) JOIN cats USING (id_cat) 
WHERE id_state = '$id'");

$images = $connection->query("SELECT * FROM  images ");

$comments = $connection->query("SELECT comment_newTime, login, com FROM states JOIN comments USING (id_state) 
WHERE id_state = '$id' AND comment_moder = 'yes' ORDER BY comment_newTime DESC ");

$informImg = $connection->query("SELECT id_state, login FROM states JOIN registrations USING (id_login) 
WHERE id_state = '$id' ");

$informImg = $informImg->fetch();
$nameDirectImg = 'user/images/' . $informImg['login'] . $informImg['id_state'];

$user = $connection->query("SELECT login FROM registrations WHERE userActive = '1'");

if ($_POST['submit']) {
    $login = htmlspecialchars($_POST['login']);
    $text = htmlspecialchars($_POST['comment']);

    foreach ($user as $us) {
        if ($login == $us['login']) {
            $writeIdCom = $connection->query("SELECT id_login FROM registrations WHERE login = '$login'");
            $writeIdCom = $writeIdCom->fetch();
            $writeIdCom = $writeIdCom['id_login'];

            $write = $connection->prepare("INSERT  comments (id_login, id_state, com, login ) 
            VALUES (:writeIdCom, :id, :text, :login)");
            $com = [
                    'writeIdCom' => $writeIdCom,
                    'id' => $id,
                    'text' => $text,
                    'login' => $login
            ];
            $write->execute($com);
        }
    }

}


?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Index.html</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
</head>
<body>
<div class="container">
    <hr>
    <ul class="nav justify-content-around align-items-center"> <!--bootstrap flex-->
        <li class="nav-item text-center">
            <img src="https://img.icons8.com/fluent/48/000000/cat.png"/>
            <h3 class="h3">All about cats</h3>
        </li>

        <nav class="navbar navbar-light bg-light mt-3">
            <form class="form-inline align-items-center"> <!--align-items-center- разместить вцентре-->
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </nav>
        <div class="d-flex">
            <div class="btn-group mt-3">
                <button type="button"  class="btn btn-outline-primary dropdown-toggle " data-toggle="dropdown"
                        style = "width : 250px" aria-haspopup="true" aria-expanded="false">
                    Войти
                </button>
                <form class="dropdown-menu p-4" method="post">
                    <label for="exampleDropdownFormEmail2"><?=$mail?></label>
                    <div class="form-group ">
                        <label for="exampleDropdownFormEmail2">Логин или email</label>
                        <input type="text" class="form-control" id="exampleDropdownFormEmail2" name="login" required>
                    </div>
                    <div class="form-group ">
                        <label for="exampleDropdownFormPassword2">Пароль</label>
                        <input type="password" class="form-control" id="exampleDropdownFormPassword2" name="password" required>
                    </div>
                    <input type="submit" class="btn btn-outline-success mt-1 " style="width: 200px" name="submit" value="Войти">

                    <li class="nav-item">
                        <a class="nav-link" href="registration.php">Зарегистрироваться</a>
                    </li>

                </form>
            </div>

        </div>

    </ul>



    <div >

    <?foreach ($state as $st):?>
        <div class="card mt-2 col-lg-8">
            <div style='display: flex; align-items: flex-end; flex-wrap: wrap'>
            <?foreach ($images as $img):?>
                    <?$nameImg = $nameDirectImg . '/' . $img['id_img'] . $img['image_title'] . '.' . $img['extension'];
                    if (file_exists($nameImg)):?>
                        <img src="<?=$nameImg?>" class="card-img-top img-thumbnail " style="width: 200px" alt="...">
                    <?endif;?>
            <?endforeach;?>
            </div>

            <div class="card-body">
                <blockquote class="cart-title blockquote text-center">
                    <p class="mb-5 mt-2 h1"><?=$st['state_title']?></p>
                </blockquote>
                <p class="card-text text-justify"><?=$st['state_content']?></p>
            </div>
            <hr>
            <p><a href="user/person.php"><?=$st['login']?></a> <?=$st['country']?></p>
        </div>

    <?endforeach;?>

    </div>

</div>



<div class="newComment">
    <h3>Комментарии могут оставлять только авторизированные пользователи</h3>
    <hr>

    <form method="post">
        <input type="text" name="login" required placeholder="Логин"><br/>
        <textarea name="comment" required placeholder="Комментарий" id="" cols="30" rows="10"></textarea><br/>
        <?if ($_GET['idUser']):?>
        <p><input type="submit" name="submit"></p>
        <?endif;?>
    </form>
</div>



<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>
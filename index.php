<?php
$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');
$state = $connection->query("SELECT id_state, state_title, login, cat_title
FROM states JOIN registrations USING (id_login) JOIN cats USING (id_cat) WHERE state_moder = 'yes'");

$user = $connection->query("SELECT id_login, email, login, userPassword FROM registrations");

if ($_POST['login']) {
    session_start();
    foreach ($user as $us) {
        if ($_POST['login'] == $us['login'] && $_POST['password'] == $us['userPassword']) {
            $_SESSION['login'] = $us['login'];
            $_SESSION['password'] = $us['userPassword'];
            $loginId = $us['id_login'];
            header("Location:user.php?loginId=$loginId");
        }
        $mail = 'Неверный логин или пароль';
    }
}

if ($_POST['reg']) {
    header('Location:registration.php');
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
<div class=" d-flex flex-column mt-5 align-items-center">

	<?foreach ($state as $st):?>
    <div class="card mt-2" style="width: 30rem;">
        <h5 class="card-header"><?=$st['state_title']?></h5>
        <div class="card-body">
            <h5 class="card-title"><?=$st['cat_title']?></h5>
            <p class="card-text"><?=$st['login']?></p>
            <div class="card-footer text-right">
                <a  href="article.php?stateId=<?=$st['id_state']?>" class="btn btn-primary">Узнать больше</a>
            </div>

        </div>
    </div>
    <?endforeach;?>

</div>
</div>



<script src="https://code.jquery.com/jquery-3.2.1.slim.min.js"
        integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js"
        integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js"
        integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
</body>
</html>

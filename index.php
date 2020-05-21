<?php
$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');
$state = $connection->query("SELECT id_state, state_title, login, cat_title
FROM states JOIN registrations USING (id_login) JOIN cats USING (id_cat) WHERE state_moder = 'yes'");



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
            <li class="nav-item">
                <a class="nav-link" href="registration.php">Зарегистрироваться</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" href="login.php">Войти</a>
            </li>
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
                <a  href="article.php?id=<?=$st['id_state']?>" class="btn btn-primary">Узнать больше</a>
            </div>

        </div>
    </div>
    <?endforeach;?>

</div>
</div>



<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js"
        integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>
</body>
</html>

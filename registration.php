<?php
$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');

function randomAuthKey () {
    $char = '1234567890qwertyuiopasdfghjklzxcvbnmMNBVCXZASDFGHJKLPOIUYTREWQ';
    $rand = '';
    for ($i=0; $i < 20; $i++) {
        $rand .= $char[rand(0, strlen($char)-1)];
    }
    return $rand;
}

$err = '';
$mail = '';

if ($_POST['name']) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $country = $_POST['country'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    $authKey = randomAuthKey();
    if ($password != $_POST['password1']) {
        $err = 'Пароли не совпадают, повторите попытку';
    } else {
        $data = $connection->query("INSERT INTO registrations 
        (userName, surname, country, email, login, userPassword, authKey) VALUES 
        ('$name', '$surname', '$country', '$email', '$login', '$password', '$authKey')");

        if ($data) {
            mail($email, 'Подтвердите почту', "http://writesite/?auth=$authKey");
            $mail = 'Письмо отправлено. Подтвердите почту';
            $name = '';
            $surname = '';
            $country = '';
            $email = '';
            $login = '';
            $password = '';
        } else {
            $findUser = $connection->query("SELECT * FROM registrations WHERE email = '$email'");
            $findUser = $findUser->fetch();

            if ($findUser['validation']) {
                $mail =  'Вы уже зарегестрированы, войдите на сайт';
                $name = '';
                $surname = '';
                $country = '';
                $email = '';
                $login = '';
                $password = '';
            } else {
                $mail = 'Вы так и не подтвердили почту';
                $name = '';
                $surname = '';
                $country = '';
                $email = '';
                $login = '';
                $password = '';
            }
        }
    }
}

if ($_GET['auth']) {
    $auth = $_GET['auth'];
    $connection->query("UPDATE registrations SET userValidation = true, 
    newTime = current_timestamp WHERE authKey = '$auth'");
    $mail = 'Выша почта подтверждена, войдите на сайт';
}


if ($_GET['login']) {
    header('Location:login.php');
}

?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<h2 class="h2"><?=$err?></h2>
<h2 class="h2"><?=$mail?></h2>

<div class="container">
    <div class="card p-2 w-50 mx-auto">
        <div class="card-header text-center">
            <img src="https://img.icons8.com/fluent/48/000000/cat.png"/>
            <h3 class="h3">Register for know more</h3>
        </div>
  <form class="card-body text-center" method="post">
        <input type="text" class="form-control" name="name" value="<?=$name?>" required placeholder="Имя"><br/>
        <input type="text" class="form-control" name="surname" value="<?=$surname?>" required placeholder="Фамилия"><br/>
        <input type="text" class="form-control" name="country" value="<?=$country?>" required placeholder="Страна"><br/>
        <input type="email" class="form-control" name="email" value="<?=$email?>" required placeholder="Почта"><br/>
        <input type="text" class="form-control" name="login" value="<?=$login?>" required placeholder="Логин"><br/>
        <input type="password" class="form-control" name="password" required placeholder="Введите пароль"><br/>
        <input type="password" class="form-control" name="password1" required placeholder="Повторите пароль"><br/>
        <input type="submit" class="btn btn-success mx-auto"  name="registration" value="Зарегестрироваться"><br/>
    </form>
        <div class="card-footer d-flex justify-content-center align-items-center">
        <p >Уже зарегестрированы?</p>

        <form method="get">
            <input class="btn btn-primary ml-2" type="submit" name="login" value="Войти">
        </form>
        </div>
</div>
</div>
<hr>



<?php
$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');
session_start();

if ($_POST['login']) {
    $login = htmlspecialchars($_POST['login']);
    $password = htmlspecialchars($_POST['password']);
    $user = $connection->query("SELECT id_login, email, login, userPassword, userValidation FROM registrations 
    WHERE (login or email) = '$login' AND userPassword = '$password' AND userValidation = true ");

    $userDebik = $connection->query("SELECT email, login, userPassword, userValidation FROM registrations 
    WHERE (login or email) = '$login' AND userPassword = '$password' AND userValidation = false ");

    $userDebik = $userDebik->fetch();
    $user = $user->fetch();

    if ($userDebik) {
        echo "<h1>Почту подтверди, заебал бля. Додик</h1>";
    }
    elseif ($user) {
        $_SESSION['login'] = $login;
        $_SESSION['password'] = $password;
        $loginId = $user['id_login'];
        header("Location:user.php?id=$loginId");
    } else {
        echo 'Неверный логин или пароль';
    }

}

?>
<!doctype html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
          integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <title>Login</title>
</head>
<body>
<div>
    <form  class="card w-25 mx-auto p-2" method="post">
        <div class="card-header">
            <h2 class="h2 header-title">Add your data for entering </h2>
        </div>
        <div class="form-group">
            <label for="login">Email address</label>
            <input type="text" name="login" required placeholder="Введите логин или email" class="form-control" id="login">
            <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input id='password' class="form-control" type="password" name="password" required placeholder="Пароль">
        </div>
        <input class="btn btn-primary" type="submit">
    </form>
</div>
</body>
</html>


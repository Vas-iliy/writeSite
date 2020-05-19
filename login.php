<?php
$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');
session_start();

if ($_POST['login']) {
    $login = $_POST['login'];
    $password = $_POST['password'];
    $user = $connection->query("SELECT email, login, password, validation FROM registrations 
    WHERE (login or email) = '$login' AND password = '$password' AND validation = true ");

    $userDebik = $connection->query("SELECT email, login, password, validation FROM registrations 
    WHERE (login or email) = '$login' AND password = '$password' AND validation = false ");

    $userDebik = $userDebik->fetch();
    $user = $user->fetch();

    if ($userDebik) {
        echo "<h1>Почту подтверди, заебал бля. Додик</h1>";
    }
    elseif ($user) {
        $_SESSION['login'] = $login;
        $_SESSION['password'] = $password;
        header('Location:state.php');
    } else {
        echo 'Неверный логин или пароль';
    }

}

?>

<div>
    <form method="post">
        <input type="text" name="login" required placeholder="Введите логин или email">
        <input type="password" name="password" required placeholder="Пароль">
        <input type="submit">
    </form>
</div>

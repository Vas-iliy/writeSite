<?php
$err = '';
if ($_POST['name']) {
    $name = $_POST['name'];
    $surname = $_POST['surname'];
    $country = $_POST['country'];
    $email = $_POST['email'];
    $login = $_POST['login'];
    $password = $_POST['password'];
    if ($password != $_POST['password1']) {
        $err = 'Пароли не совпадают, повторите попытку';
    } else {

    }
}

?>

<h2><?=$err?></h2>

<div>
    <form method="post">
        <input type="text" name="name" value="<?=$name ?? ''?>" required placeholder="Имя"><br/>
        <input type="text" name="surname" value="<?=$surname ?? ''?>" required placeholder="Фамилия"><br/>
        <input type="text" name="country" value="<?=$country ?? ''?>" required placeholder="Страна"><br/>
        <input type="email" name="email" value="<?=$email ?? ''?>" required placeholder="Почта"><br/>
        <input type="text" name="login" value="<?=$login ?? ''?>" required placeholder="Логин"><br/>
        <input type="password" name="password" required placeholder="Введите пароль"><br/>
        <input type="password" name="password1" required placeholder="Повторите пароль"><br/>
        <input type="submit" name="registration" value="Зарегестрироваться"><br/>
    </form>
</div>
<hr>
<p>Уже зарегестрированы?</p>
<form method="post">
    <input type="submit" name="login" value="Войти">
</form>

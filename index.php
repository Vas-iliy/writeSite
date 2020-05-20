<?php
$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');
$state = $connection->query("SELECT id_state, state_title, login, cat_title
FROM states JOIN registrations USING (id_login) JOIN cats USING (id_cat) WHERE state_moder = 'yes'");



?>

<hr>
<a href="registration.php">Зарегестрироваться</a>
<a href="login.php">Войти</a>

<div>
    <?foreach ($state as $st):?>
        <div>
            <h2><?=$st['state_title']?></h2>
            <h3><?=$st['cat_title']?></h3>
            <h3><?=$st['login']?></h3>

            <hr>
            <a href="article.php?id=<?=$st['id_state']?>">Читать далее</a>
        </div>
    <?endforeach;?>
</div>
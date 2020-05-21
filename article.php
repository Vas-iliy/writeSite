<?php

$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');
$id = $_GET['id'];
$state = $connection->query("SELECT state_title, state_content, state_newTime, userName, 
surname, country, login, cat_title FROM states JOIN registrations USING (id_login) JOIN cats USING (id_cat) 
WHERE id_state = '$id'");

$images = $connection->query("SELECT id_img, image_title, extension FROM  images ");

$comments = $connection->query("SELECT comment_newTime, login, com FROM states JOIN comments USING (id_state) 
WHERE id_state = '$id' AND comment_moder = 'yes' ORDER BY comment_newTime DESC ");

$informImg = $connection->query("SELECT id_state, login FROM states JOIN registrations USING (id_login) 
WHERE id_state = '$id' ");

$informImg = $informImg->fetch();
$nameDirectImg = 'images/' . $informImg['login'] . $informImg['id_state'];

$user = $connection->query("SELECT login FROM registrations WHERE userActive = '1'");
$dontUser = $connection->query("SELECT login FROM registrations WHERE userActive = '0'");

if ($_POST['go']) {
    $login = $_POST['login'];
    $text = $_POST['comment'];

    foreach ($dontUser as $dont) {
        if ($login == $dont['login']) {
            echo 'Войдите на сайт';
        }
    }

    foreach ($user as $us) {
        if ($login == $us['login']) {
            $writeIdCom = $connection->query("SELECT id_login FROM registrations WHERE login = '$login'");
            $writeIdCom = $writeIdCom->fetch();
            $writeIdCom = $writeIdCom['id_login'];
            $connection->query("INSERT INTO comments (id_login, id_state, com, login ) 
            VALUES ('$writeIdCom', '$id', '$text', '$login')");
        }
    }

}

?>

<div class="article">
    <?foreach ($state as $st):?>
    <div class="state">
        <h2><?=$st['state_title']?></h2>
        <h3><?=$st['cat_title']?></h3>
    </div>
    <div class="user">
        <h3><a href=""><?=$st['login']?></a></h3>
        <h4><?=$st['userName']?></h4>
        <h4><?=$st['surname']?></h4>
        <h4><?=$st['country']?></h4>
    </div>
    <?endforeach;?>
</div>

<div class="images">
    <?foreach ($images as $img):?>
    <div>
        <?$nameImg = $nameDirectImg. '/' . $img['id_img'] . $img['image_title'] . '.' . $img['extension'];
        if (file_exists($nameImg)):?>
        <img src="<?=$nameImg?>" width="300">
        <?endif;?>
    </div>
    <?endforeach;?>
</div>

<div class="newComment">
    <h3>Комментарии могут оставлять только авторизированные пользователи</h3>
    <hr>

    <form method="post">
        <input type="text" name="login" required placeholder="Логин"><br/>
        <textarea name="comment" required placeholder="Комментарий" id="" cols="30" rows="10"></textarea><br/>
        <input type="submit" name="go">
    </form>
</div>
<?php

$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');

$stateId = (int)$_GET['stateId'];

//Все старые элементы
$oldState = $connection->query("SELECT state_title, state_content, cat_title FROM states 
JOIN cats USING (id_cat) WHERE id_state = '$stateId'");
$oldTegs = $connection->query("SELECT teg_title FROM (tegs JOIN states_tegs USING (id_teg)) 
JOIN states USING (id_state) WHERE id_state = '$stateId'");
$oldState = $oldState->fetch();

$oldTitle = $oldState['state_title'];
$oldContent = $oldState['state_content'];
$oldCat = $oldState['cat_title'];
$oldTeg = '';

foreach ($oldTegs as $teg) {
    $oldTeg .= $teg['teg_title'] . ',';
}

$searchLog = $connection->query("SELECT id_login, login FROM registrations JOIN states USING (id_login) WHERE id_state = '$stateId'");
$searchLog = $searchLog->fetch();
$id_login = $searchLog['id_login'];





//Редактирование статьи
if (isset($_POST['state'])) {
    $title = htmlspecialchars($_POST['title']);
    $cat = htmlspecialchars($_POST['cat']);
    $content = htmlspecialchars($_POST['content']);
    $cat = strtolower($cat);



    $searchCat = $connection->query("SELECT id_cat FROM cats WHERE cat_title = '$cat'");
    $searchCat = $searchCat->fetch();
    if (!$searchCat) {
        $wr= $writeCat = $connection->prepare("INSERT INTO cats (cat_title) VALUE (:cat)");
        $wr->bindParam(':cat', $cat);
        $wr->execute();
    }

    $search = $connection->query("SELECT id_cat FROM cats WHERE cat_title = '$cat'");
    $search = $search->fetch();
    $id_cat = $search['id_cat'];

    $writeState = $connection->prepare("UPDATE states SET id_cat = :id_cat, state_title = :title,
    state_content = :content WHERE id_state = '$stateId'");
    $wS = [
        'id_cat' => $id_cat,
        'title' => $title,
        'content' => $content
    ];
    $writeState->execute($wS);

    if ($_POST['tegs']) {
        $tegs = $_POST['tegs'];
        $tegs = strtolower($tegs);
        $tegs = explode(',', $tegs);
        $countTeg = count($tegs);

        for ($i=0; $i < $countTeg; $i++) {
            $teg = $tegs[$i];
            $searchTeg = $connection->query("SELECT id_teg FROM tegs WHERE teg_title = '$teg' ");
            $searchTeg =$searchTeg->fetch();
            if (!$searchTeg) {
                $tag = $connection->prepare("INSERT INTO tegs (teg_title) VALUE (:teg)");
                $tag->bindParam(':teg', $teg);
                $tag->execute();
                $searchTeg = $connection->query("SELECT id_teg FROM tegs WHERE teg_title = '$teg' ");
                $searchTeg = $searchTeg->fetch();
                $id_teg = $searchTeg['id_teg'];
                $connection->query("INSERT INTO states_tegs (id_state ,id_teg) VALUES ('$stateId','$id_teg')");
            }
        }
    }

    //начинаем работу с файлом
    $files = array();
    $diff = count($_FILES['file']) - count($_FILES['file'], COUNT_RECURSIVE);
    if ($diff == 0) {
        $files = array($_FILES['file']);
    } else {
        foreach($_FILES['file'] as $k => $l) {
            foreach($l as $i => $v) {
                $files[$i][$k] = $v;
            }
        }
    }


    $nameDir = 'images/' . $searchLog['login'] . $stateId;

    foreach ($files as $file) {
        $fileName = strval($file['name']);
        $fileType = strval($file['name']);
        $fileTmp_name = strval($file['tmp_name']);
        $fileError = strval($file['error']);
        $fileSize = strval($file['size']);

        $fileExtension = strtolower(end(explode('.', $fileName)));

        if (count(explode('.', $fileName)) > 2) {
            for ($i=0; $i<count(explode('.', $fileName)); $i++) {
                $fileName .= explode('.', $fileName)[$i] . '.';
            }
        } else {
            $fileName = explode('.', $fileName)[0];
        }

        $fileName = preg_replace('/[0-9]/', '',  $fileName);

        $arrExtension = ['jpg', 'jpeg', 'png'];
        if (in_array($fileExtension, $arrExtension)) {
            if ($fileSize < 5000000) {
                if ($fileError == 0) {
                    $new = $connection->query("INSERT INTO images (id_state, id_login, image_title, extension) 
                    VALUES ('$stateId', '$id_login', '$fileName', '$fileExtension')");

                    $lastId = $connection->query("SELECT MAX(id_img) FROM images");
                    $lastId = $lastId->fetch();
                    $lastId = $lastId[0];

                    $fileNameNew = $lastId . $fileName . '.' . $fileExtension;
                    $fileDestination = $nameDir . '/' . $fileNameNew;
                    move_uploaded_file($fileTmp_name, $fileDestination);

                } else {
                    echo 'Что-то пошло не так';
                }
            } else {
                echo 'Слишком большой размер файла';
            }
        } else {
            echo 'Неверный вормат файла';
        }
    }

    $moderState = $connection->query("SELECT id_state FROM states WHERE state_moder = 'yes' 
    AND id_state = '$stateId'");
    $moderState = $moderState->fetch();
    if ($moderState['id_state']) {
        $connection->query("UPDATE states SET state_moder = NULL, state_newTime = current_timestamp 
        WHERE id_state = '$stateId'");
    }
}

if ($_POST) {
    header("Location:redactorState.php?stateId=$stateId");
}

$imgData = $connection->query("SELECT id_img, image_title, extension FROM images");

echo "<div style='display: flex; align-items: flex-end; flex-wrap: wrap'>";

foreach ($imgData as $img) {
    $image = "images/" . $searchLog['login'] . $stateId . '/'  . $img['id_img'] . $img['image_title'] . '.' . $img['extension'];

    if (file_exists($image)) {
        echo "<div>";
        echo "<img width='200'  src='$image'>";
        echo "<form method='post'><input type='submit'  name='delete" . $img['id_img'] . "' value='Удалить'></form>";
        echo "</div>";
    }

    $delete = "delete" . $img['id_img'];
    if (isset($_POST[$delete])) {
        $imageId = $img['id_img'];
        //удаление из БД картинки с айди, кнопку которого нажали
        $connection->query("DELETE FROM images WHERE id_img = '$imageId'");

        //так же картинку удаляем и с сайта
        if (file_exists($image)) {
            unlink($image);
        }
    }
}

echo "</div>";




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
    <ul class="nav justify-content-around align-items-center">
        <li class="nav-item text-center">
            <img src="https://img.icons8.com/fluent/48/000000/cat.png"/>
            <h3 class="h3">All about cats</h3>
        </li>

        <nav class="navbar navbar-light bg-light mt-3">
            <form class="form-inline align-items-center">
                <input class="form-control mr-sm-2" type="search" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success my-2 my-sm-0" type="submit">Search</button>
            </form>
        </nav>

        <div class="btn-group mt-3">
            <button type="button" class="btn btn-outline-primary dropdown-toggle " data-toggle="dropdown"
                    style = "width : 170px" aria-haspopup="true" aria-expanded="false">
                <?=$searchLog['login']?>
            </button>
            <div class="dropdown-menu">
                <a class="dropdown-item" href="user/person.php?loginId=<?=$id_login?>">Моя страница</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="user/listMyStates.php?loginId=<?=$id_login?>">Список статей</a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item" href="user.php?loginId=<?=$id_login?>">На главную</a> //аааа, это не тот ауди бляяяя
                <div class="dropdown-divider"></div>
                <form method="post"><input class="dropdown-item" type="submit" name="exit" value="Выйти"></form>
            </div>
        </div>

    </ul>
</div>

<h2>Вы зашли для редкатирования статьи?</h2>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" required value="<?=$oldTitle?>"><br/>
    <input type="text" name="cat" required value="<?=$oldCat?>"><br/>
    <textarea name="content" id="" cols="30" rows="10" required><?=$oldContent?></textarea><br/>

    <textarea name="tegs" id="" cols="30" rows="10"><?=$oldTeg?></textarea><br/></br>
    <input type="file" name="file[]" multiple required> <br/> </br>
    <input type="submit" name="state" >

</form>

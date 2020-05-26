<?php

$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');
session_start();
if (!$_SESSION['login']) {
    header('Location:../index.php');
}

$loginId = (int)$_GET['loginId'];
if ($_POST['exit']) {
    session_destroy();
    header('Location:../index.php');
}

if (isset($_POST['state'])) {
    $title = htmlspecialchars($_POST['title']);
    $cat = htmlspecialchars($_POST['cat']);
    $content = htmlspecialchars($_POST['content']);
    $cat = strtolower($cat);

    $searchCat = $connection->query("SELECT id_cat FROM cats WHERE cat_title = '$cat'");
    $searchCat = $searchCat->fetch();
    if (!$searchCat) {
        $write = $writeCat = $connection->prepare("INSERT INTO cats (cat_title) VALUE (:cat)");
        $write->bindParam(':cat', $cat);
        $write->execute();
    }

    $search = $connection->query("SELECT id_cat FROM cats WHERE cat_title = '$cat'");
    $search = $search->fetch();
    $id_cat = $search['id_cat'];

    $writeState = $connection->prepare("INSERT INTO states (id_login, id_cat, state_title, state_content) 
    VALUES (:id, :id_cat, :title, :content) ");
    $wS = [
            'id' => $loginId,
        'id_cat' => $id_cat,
        'title' => $title,
        'content' => $content
    ];
    $writeState->execute($wS);

    if ($_POST['tegs']) {
        $tegs = htmlspecialchars($_POST['tegs']);
        $tegs = strtolower($tegs);
        $tegs = explode(',', $tegs);
        $countTeg = count($tegs);
        $searchState = $connection->query("SELECT MAX(id_state) FROM states");
        $searchState = $searchState->fetch();
        $id_state = $searchState[0];

        for ($i=0; $i < $countTeg; $i++) {
            $teg = $tegs[$i];
            $searchTeg = $connection->query("SELECT id_teg FROM tegs WHERE teg_title = '$teg' ");
            $searchTeg =$searchTeg->fetch();
            if (!$searchTeg) {
                $tag = $connection->prepare("INSERT INTO tegs (teg_title) VALUE (:teg)");
                $tag->bindParam(':teg', $teg);
                $tag->execute();
            }

            $searchTeg = $connection->query("SELECT id_teg FROM tegs WHERE teg_title = '$teg' ");
            $searchTeg = $searchTeg->fetch();
            $id_teg = $searchTeg['id_teg'];

            $writeStates_tegs = $connection->query("INSERT INTO states_tegs (id_state ,id_teg) 
            VALUES ('$id_state','$id_teg')");
        }
    }

    $searchState = $connection->query("SELECT MAX(id_state) FROM states");
    $searchState = $searchState->fetch();
    $id_state = $searchState[0];

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

    //создание директории с картинками
    $nameUser = $connection->query("SELECT login FROM registrations WHERE id_login = '$loginId'");
    $nameUser = $nameUser->fetch();
    $nameDir = 'images/' . $nameUser['login'] . $id_state;
    mkdir($nameDir);

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
                    VALUES ('$id_state', '$loginId', '$fileName', '$fileExtension')");

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
}

?>
<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
      integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">




<div class="container">
    <div class="card p-2 w-50 mx-auto">
        <div class="card-header text-center">
            <img src="https://img.icons8.com/fluent/48/000000/cat.png"/>
            <h3 class="h3">Register for know more</h3>
        </div>
        <form class="card-body text-center" method="post">
            <input type="text" class="form-control" name="title" required placeholder="Название статьи"><br/>
            <input type="text" class="form-control" name="cat" required placeholder="Название Категории"><br/>
            <textarea name="content" class="form-control" id="" cols="30" rows="10" required placeholder="Текст статьи"></textarea><br/>
            <textarea name="tegs" class="form-control" id="" cols="30" rows="10" placeholder="Введите теги через запятую"></textarea><br/>
            <input type="file" name="file[]" multiple> <br/> <br/>
            <button type="submit" class="btn btn-success mx-auto" name="state" >Добавить статью</button>
        </form>
        <div class="card-footer  ">
            <div class="row justify-content-center">
                <p class="m-0 p-0">Передумали?</p>
                <a class="ml-3 "href="../user.php?loginId=<?=$loginId?>">Обратно</a>
            </div>
        </div>
    </div>
</div>
<hr>




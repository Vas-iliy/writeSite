<?php

$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');

$id = $_GET['id'];

//Все старые элементы
$oldState = $connection->query("SELECT state_title, state_content, cat_title FROM states 
JOIN cats USING (id_cat) WHERE id_state = '$id'");
$oldTegs = $connection->query("SELECT teg_title FROM (tegs JOIN states_tegs USING (id_teg)) 
JOIN states USING (id_state) WHERE id_state = '$id'");
$oldState = $oldState->fetch();

$oldTitle = $oldState['state_title'];
$oldContent = $oldState['state_content'];
$oldCat = $oldState['cat_title'];
$oldTeg = '';

foreach ($oldTegs as $teg) {
    $oldTeg .= $teg['teg_title'] . ',';
}







//Редактирование статьи
if (isset($_POST['state'])) {
    $title = $_POST['title'];
    $cat = $_POST['cat'];
    $content = $_POST['content'];
    $cat = strtolower($cat);



    $searchCat = $connection->query("SELECT id_cat FROM cats WHERE cat_title = '$cat'");
    $searchCat = $searchCat->fetch();
    if (!$searchCat) {
        $writeCat = $connection->query("INSERT INTO cats (cat_title) VALUE ('$cat')");
    }

    $search = $connection->query("SELECT id_cat FROM cats WHERE cat_title = '$cat'");
    $search = $search->fetch();
    $id_cat = $search['id_cat'];

    $writeState = $connection->query("INSERT INTO states (id_login, id_cat, state_title, state_content) 
    VALUES ('$id', '$id_cat', '$title', '$content') ");

    if ($_POST['tegs']) {
        $tegs = $_POST['tegs'];
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
                $connection->query("INSERT INTO tegs (teg_title) VALUE ('$teg')");
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
    $nameUser = $connection->query("SELECT login FROM registrations WHERE id_login = '$id'");
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
                    VALUES ('$id_state', '$id', '$fileName', '$fileExtension')");

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
    AND id_state = '$id'");
    $moderState = $moderState->fetch();
    if ($moderState['id_state']) {
        $connection->query("UPDATE states SET state_moder = NULL, time = current_timestamp 
        WHERE id_state = '$id'");
    }
}




?>
<h2>Вы зашли для редкатирования статьи?</h2>
<a href="../user.php?id=<?=$id?>">обратно</a>
<form method="post" enctype="multipart/form-data">
    <input type="text" name="title" required value="<?=$oldTitle?>"><br/>
    <input type="text" name="cat" required value="<?=$oldCat?>"><br/>
    <textarea name="content" id="" cols="30" rows="10" required><?=$oldContent?></textarea><br/>

    <textarea name="tegs" id="" cols="30" rows="10"><?=$oldTeg?></textarea><br/></br>
    <input type="file" name="file[]" multiple required> <br/> </br>
    <input type="submit" name="state" >

</form>

<?php

$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');

$id = $_GET['id'];

if ($_POST['state']) {
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



}


?>


    <form method="post">
        <input type="text" name="title" required placeholder="Название статьи"><br/>
        <input type="text" name="cat" required placeholder="Название Категории"><br/>
        <textarea name="content" id="" cols="30" rows="10" required placeholder="Текст статьи"></textarea><br/>
        <textarea name="tegs" id="" cols="30" rows="10" placeholder="Введите теги через запятую"></textarea><br/>
        <input type="submit" name="state" > <br/>
    </form>
    <form method="post" enctype="multipart/form-data">
        <input type="file" name="file[]" multiple required>
        <input type="submit">
    </form>



<?php
class State {
   /* public $write;
    public $file;*/

    /*function __construct( $content)
    {
        $this->content = $content;
    }*/

    public function connect () {
        $connection = new PDO('mysql:host=localhost; dbname=write', 'root','root');
        return $connection;
    }

    public function checkKey ($key, $link, $login, $stateId) {
        if ($key == 'editing') {
            $this->renderingOld($link, $login, $stateId);
        }
        else if ($key == 'new') {
            $this->renderingNew($link);
        }
    }

    public function writeState ($key, $stateId, $id_cat, $title, $content, $loginId) {
        $connect = $this->connect();
        if ($key == 'editing') {
            $writeState = $connect->prepare("UPDATE states SET id_cat = :id_cat, state_title = :title,
            state_content = :content WHERE id_state = '$stateId'");
            $wS = [
                'id_cat' => $id_cat,
                'title' => $title,
                'content' => $content
            ];
            $writeState->execute($wS);
        }
        elseif ($key == 'new') {
            $writeState = $connect->prepare("INSERT INTO states (id_login, id_cat, state_title, state_content) 
            VALUES (:id, :id_cat, :title, :content) ");
            $wS = [
                'id' => $loginId,
                'id_cat' => $id_cat,
                'title' => $title,
                'content' => $content
            ];
            $writeState->execute($wS);
        }
    }


    public function checkData ($stateId) {
        $connect = $this->connect();
        $data = $connect->query("SELECT id_state FROM state WHERE id_state = '$stateId'");
        $data = $data->fetch();
        if ($data) {
            return true;
        } else {
            return false;
        }
    }

    public function renderingNew ($link, $oldTitle, $oldCat, $oldContent, $oldTeg) {
        echo "<link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css\"
      integrity=\"sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T\" crossorigin=\"anonymous\">


<div class=\"container\">
    <div class=\"card p-2 w-50 mx-auto\">
        <div class=\"card-header text-center\">
            <img src=\"https://img.icons8.com/fluent/48/000000/cat.png\"/>
            <h3 class=\"h3\">Register for know more</h3>
        </div>
        <form class=\"card-body text-center\" method=\"post\" enctype=\"multipart/form-data\">
            <input type=\"text\" class=\"form-control\" name=\"title\" required value=\"{$oldTitle}\" placeholder=\"Название статьи\"><br/>
            <input type=\"text\" class=\"form-control\" name=\"cat\" required value=\"{$oldCat}\" placeholder=\"Название Категории\"><br/>
            <textarea name=\"content\" class=\"form-control\" id=\"\" cols=\"30\" rows=\"10\" required placeholder=\"Текст статьи\">{$oldContent}</textarea><br/>
            <textarea name=\"tegs\" class=\"form-control\" id=\"\" cols=\"30\" rows=\"10\" placeholder=\"Введите теги через запятую\">{$oldTeg}</textarea><br/>
            <input type=\"file\" name=\"file[]\" multiple> <br/> <br/>
            <button type=\"submit\" class=\"btn btn-success mx-auto\" name=\"state\" >Добавить статью</button>
        </form>
        <div class=\"card-footer  \">
            <div class=\"row justify-content-center\">
                <p class=\"m-0 p-0\">Передумали?</p>
                <a class=\"ml-3 \"href=\"{$link}\">Обратно</a>
            </div>
        </div>
    </div>
</div>
<hr>";

    }

    public function renderingOld ($link, $oldTitle, $oldCat, $oldContent, $oldTeg, $login, $stateId) {
        $this->renderingNew($link, $oldTitle, $oldCat, $oldContent, $oldTeg);
        $connect = $this->connect();
        $imgData = $connect->query("SELECT id_img, image_title, extension FROM images");
        echo "<div style='display: flex; align-items: flex-end; flex-wrap: wrap'>";
        foreach ($imgData as $img) {
            $image = "images/" . $login . $stateId . '/'  . $img['id_img'] . $img['image_title'] . '.' . $img['extension'];

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
                $connect->query("DELETE FROM images WHERE id_img = '$imageId'");

                //так же картинку удаляем и с сайта
                if (file_exists($image)) {
                    unlink($image);
                }
            }
        }
        echo "</div>";

    }
}


$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');
session_start();
if (!$_SESSION['login']) {
    header('Location:../v_index.php');
}


if ($_POST['exit']) {
    session_destroy();
    header('Location:../v_index.php');
}

$loginId = (int)$_GET['loginId'];
$stateId = (int)$_GET['stateId'];

if ($stateId) {
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

    //Тут идет запись в таблицу статей, если ключ new
    $w = new State();
    $w->writeState($_GET['key'], $stateId ?? '',$id_cat, $title, $content, $loginId ?? '');

    if ($_POST['tegs']) {
        $tegs = htmlspecialchars($_POST['tegs']);
        $tegs = strtolower($tegs);
        $tegs = explode(',', $tegs);
        $countTeg = count($tegs);
        //Тут мы берем максимальный индекс, а потом записываем в таблицу статья-тег. Это только для новой
        if ($loginId) {
            $searchState = $connection->query("SELECT MAX(id_state) FROM states");
            $searchState = $searchState->fetch();
            $id_state = $searchState[0];
        }

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

                if (!$id_state) {
                    $connection->query("INSERT INTO states_tegs (id_state ,id_teg) 
                VALUES ('$stateId','$id_teg')");
                }
                else {
                    $connection->query("INSERT INTO states_tegs (id_state ,id_teg) 
                VALUES ('$id_state','$id_teg')");
                }
            }



        }
    }

    if ($loginId) {
        $searchState = $connection->query("SELECT MAX(id_state) FROM states");
        $searchState = $searchState->fetch();
        $id_state = $searchState[0];
    }

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

    if ($loginId) {
        $nameUser = $connection->query("SELECT login FROM registrations WHERE id_login = '$loginId'");
        $nameUser = $nameUser->fetch();
        $nameDir = 'images/' . $nameUser['login'] . $id_state;
        mkdir($nameDir);
    } else {
        $nameDir = 'images/' . $searchLog['login'] . $stateId;
    }

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
        if ($fileSize > 0) {
            if (in_array($fileExtension, $arrExtension)) {
                if ($fileSize < 5000000) {
                    if ($fileError == 0) {
                        $new = $connection->query("INSERT INTO images (id_state, id_login, image_title, extension) 
                    VALUES ('$stateId', '$loginId', '$fileName', '$fileExtension')");

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

    if ($stateId) {
        $moderState = $connection->query("SELECT id_state FROM states WHERE state_moder = 'yes' 
        AND id_state = '$stateId'");
        $moderState = $moderState->fetch();
        if ($moderState['id_state']) {
            $connection->query("UPDATE states SET state_moder = NULL, state_newTime = current_timestamp 
            WHERE id_state = '$stateId'");
        }
    }
}

if ($stateId) {
    if ($_POST) {
        header("Location:states.php?stateId=$stateId&key=editing");
    }
    $link = "listMyStates.php?loginId=$id_login";
    $rendering = new State();
    $rendering->renderingOld($link, $oldTitle, $oldCat, $oldContent, $oldTeg, $searchLog['login'], $stateId);
}
elseif ($loginId) {
    $link = "../user.php?loginId=$loginId";
    $rendering = new State();
    $rendering->renderingNew($link, '', '', '', '');
}


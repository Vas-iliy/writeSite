<?php
class State {
    public $content;
    public $file;

    /*function __construct( $content)
    {
        $this->content = $content;
    }*/

    public function checkKey ($key) {
        if ($key = 'new') {
            $this->renderingNew();
        }
        if ($key = 'editing') {
            $this->renderingOld();
        }
    }

    public function checkData ($stateId) {
        $connection = new PDO('mysql:host=localhost; dbname=write', 'root','root');
        $data = $connection->query("SELECT id_state FROM state WHERE id_state = '$stateId'");
        $data = $data->fetch();
        if ($data) {
            return true;
        } else {
            return false;
        }
    }

    public function renderingNew ($link) {
        echo "<link rel=\"stylesheet\" href=\"https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css\"
      integrity=\"sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T\" crossorigin=\"anonymous\">


<div class=\"container\">
    <div class=\"card p-2 w-50 mx-auto\">
        <div class=\"card-header text-center\">
            <img src=\"https://img.icons8.com/fluent/48/000000/cat.png\"/>
            <h3 class=\"h3\">Register for know more</h3>
        </div>
        <form class=\"card-body text-center\" method=\"post\" enctype=\"multipart/form-data\">
            <input type=\"text\" class=\"form-control\" name=\"title\" required placeholder=\"Название статьи\"><br/>
            <input type=\"text\" class=\"form-control\" name=\"cat\" required placeholder=\"Название Категории\"><br/>
            <textarea name=\"content\" class=\"form-control\" id=\"\" cols=\"30\" rows=\"10\" required placeholder=\"Текст статьи\"></textarea><br/>
            <textarea name=\"tegs\" class=\"form-control\" id=\"\" cols=\"30\" rows=\"10\" placeholder=\"Введите теги через запятую\"></textarea><br/>
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

    public function renderingOld ($link, $login, $stateId) {
        $this->renderingNew($link);
        $connection = new PDO('mysql:host=localhost; dbname=write', 'root','root');
        $imgData = $connection->query("SELECT id_img, image_title, extension FROM images");
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
                $connection->query("DELETE FROM images WHERE id_img = '$imageId'");

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
    }}
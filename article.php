<?php
$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');
$id = $_GET['id'];
$state = $connection->query("SELECT states.title, states.content, states.newTime, registrations.name, 
registrations.surname, registrations.country, registrations.login, cats.title 
FROM states JOIN registrations USING (id_login) JOIN cats USING (id_cat)  WHERE states.id_state = '$id'");

$images = $connection->query("SELECT images.id_img, images.title, images.extension 
FROM state JOIN images USING (id_state)");

$comments = $connection->query("SELECT comments.newTime, registrations.login, comments.comment 
FROM states JOIN comments USING (id_comment) JOIN registrations USING (id_login) 
WHERE states.id_state = '$id' AND comments.moder = 'yes' ORDER BY comments.newTime DESC ");

?>

<div>

</div>

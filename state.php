<?php
$connection = new PDO('mysql:host=localhost; dbname=write', 'root', 'root');
$state = $connection->query("SELECT states.id_state, states.title, registrations.login, cats.title  
FROM states JOIN registrations USING (id_login) JOIN cats USING (id_cat)");

?>

<div>
    <?foreach ($state as $st):?>
    <h2><?=$st['states.title']?></h2>
    <h3><?=$st['cats.title']?></h3>
    <h3><?=$st['registrations.login']?></h3>
        <hr>
        <a href="article.php?<?=$st['states.id_state']?>">Читать далее</a>
    <?endforeach;?>
</div>



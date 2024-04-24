<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');
    $id = $_GET['id'];
    $requete = $bdd->prepare("SELECT U.user_id, lastname, firstname, job, date_birth, P.PosteName as poste, T.title, T.comment, T.img, T.name FROM users U
    left join postes P on P.Poste_id = job
    left join teams_users TU on TU.user_id = U.user_id
    left join teams T on T.team_id = TU.team_id
    WHERE TU.team_id = :id");
    $requete->execute(
        array(
            "id" => $id
        )
    );
    $users = $requete->fetchAll();
    $title = $users[0]['title'];
    $name = $users[0]['name'];
    $comment = $users[0]['comment'];
?>

<section class="p-2 container">
    <div class="d-flex justify-content-center">
        <h1>Equipe <?=$title?></h1>
    </div>
    <div class="d-flex justify-content-center">
        <h3>Coach : <?=$name?></h3>
    </div>
    <div class="p-3 d-flex justify-content-center">
        <p>Description : <?=$comment?></p>
    </div>
    <div class="p-3 row d-flex justify-content-center">
        <div class="col-6">
            <ul>
                <?php foreach($users as $user){
        foreach($user as $key => $value)
            $user[$key] = secure($value);?>
                <li><?=$user['lastname'];?> - <?=$user['firstname'];?> / <?=$user['poste'];?></li>
                <?php }?>
            </ul>
        </div>
        <div class="p-3 col-6 d-flex justify-content-center align-items-center">
            <img src="<?=$user['img'];?>" alt="Equipe" width="200" height="200">
        </div>
    </div>
</section>

<?php
    require_once('./lib/footer.php');
?>
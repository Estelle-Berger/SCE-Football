<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');
    $requete = $bdd->prepare("SELECT U.user_id, lastname, firstname, job, P.postename, R.profil_id FROM users U 
    left join postes P on P.poste_id = U.job
    left join roles R on U.user_id = R.user_id  WHERE R.profil_id = 3");
    $requete->execute();
    $listeUsers = $requete->fetchAll();
    foreach($listeUsers as $user){
        foreach($user as $key => $value)
        $user[$key] = secure($value);}
?>
<section class="container_fluid">
    <div class="p-3">
        <div class="d-flex justify-content-center">
            <h1>La sélection</h1>
        </div>
        <div class="p-3 row d-flex justify-content-center">
            <div class="p-2 col-5 border boreder-2 rounded-2">
                <input type="hidden" name="token" value="<?=htmlspecialchars($_SESSION['token']);?>">
                <input type="hidden" name="user_id" value="<?=$user['user_id'];?>">
                <input class="form-control d-inline-flex focus-ring focus-ring-secondary py-1 px-2 text-decoration-none border rounded-2"
                type="text" name="present" value="<?=$user['firstname'];?> . <?=$user['lastname'];?> - <?=$user['postename'];?>">
            </div>
        </div>
        <div class="p-2 d-flex justify-content-center">
            <a href="./convocation.php" class="btn btn-outline-secondary" name="convocation" type="submit">Convocation</a>
        </div>
    </div>

</section>
<?php
    require_once('./lib/footer.php');
?>
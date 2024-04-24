<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');
    $id =$_GET['id'];
    $requete = $bdd->prepare("SELECT DISTINCT T.team_id, M.match_id, U.user_id, statut, lastname, firstname, job, P.postename, R.profil_id, C.present, date, hour, T.title
    FROM users U 
    left join teams_users TU on TU.user_id = U.user_id
    left join teams T on T.team_id = TU.team_id
    left join matches M on T.team_id = M.team_id
    left join postes P on P.poste_id = U.job
    left join convocations C on U.user_id = C.player AND C.match_id = M.match_id
    left join roles R on U.user_id = R.user_id 
    WHERE R.profil_id = 3 and U.statut = 1 and M.match_id = :id");
    $requete->execute(
        array(
            "id" => $id
        )
    );
    $listeUsers = $requete->fetchAll();
?>
<section class="container_fluid">
    <div class="p-3">
        <div class="d-flex justify-content-center">
            <h1>La sélection de l'équipe</h1>
        </div>
        <div class="d-flex justify-content-center">
            <div class="p-2"style="width: 18rem;">
                <?php
                $date = $listeUsers[0]['date'];
                $hour = $listeUsers[0]['hour'];
                $match_id = $listeUsers[0]['match_id'];
                    ?>
                <h4 class="text-center">Pour le match du : <?=dates($date);?></h4>
            </div>
        </div>
        <div class="p-3 row d-flex justify-content-center gap-2">
        <form method="post" action="">
            <div class="row d-flex justify-content-center flex-wrap gap-3">
                <div class="p-2 col-5 border boreder-2 rounded-2">
                <input type="hidden" name="token" value="<?=htmlspecialchars($_SESSION['token']);?>">
                <input type="hidden" name="match_id" value="<?=$match_id?>">
                
                    <?php foreach($listeUsers as $user){
        foreach($user as $key => $value)
        $user[$key] = secure($value);
                        ?>
                    <div class=" p-2">
                        <input type="hidden" name="user_id<?=$user['user_id'];?>" value="<?=$user['user_id'];?>">
                            <input class="form-check-input" type="checkbox" name="present<?=$user['user_id'];?>" value="<?=$user['firstname'];?> . <?=$user['lastname'];?> - <?=$user['postename'];?>" id="flexCheckDefault">
                            <label class="form-check-label" for="flexCheckDefault">
                            <?=$user['firstname'];?> . <?=$user['lastname'];?> - <?=$user['postename'];?>

                        </label>
                    </div>
                    <?php }?>
            </div>
            <div class="p-2 col-5 border boreder-2 rounded-2">
                <div class="result"></div>
            </div>
            </div>
            
            <div class="p-2 d-flex justify-content-center">
                <a href="./admin_match_sheet.php?id=<?=$user['match_id'];?>" type="submit">
                <input class="btn btn-outline-secondary" type="submit" name="save_selection" id="" value="Valider la convocation"></a>
            </div>
            
        </form>
        </div>
        
    </div>
    
</section>
<?php
    require_once('./lib/footer.php');
?>
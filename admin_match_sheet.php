<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');
    $id =$_GET['id'];
    $requete_match = $bdd->prepare("SELECT title, T.team_id, img, M.match_id, date, hour, opponent, img_opponent 
    FROM matches M
    left join teams T on T.team_id = M.team_id WHERE M.match_id = :id");
    $requete_match->execute(
        array(
            "id"=>$id
        )
    );
    $listeMatchs = $requete_match->fetchAll();
    foreach($listeMatchs as $match){
        foreach($match as $key => $value)
            $match[$key] = secure($value);
        $date = $match['date'];
        $hour = $match['hour'];

        $requete_users = $bdd->prepare("SELECT DISTINCT O.PosteName as job, statut, U.lastname, U.firstname, U.user_id 
        FROM convocations C 
        left join users U on C.player = U.user_id 
        left join postes O on O.Poste_id = U.job 
        WHERE C.match_id = :id and C.present = 1");
        $requete_users->execute(
            array(
                "id"=>$id
            )
        );
        $listeUsers = $requete_users->fetchAll();
?>
<section>
    <form method="post" action="" enctype="multipart/form-data">
    <input type="hidden" name="token" value="<?=htmlspecialchars($_SESSION['token']);?>">
    <div class="p-2 d-flex justify-content-center">
        <div class="p-2 border rounded-2" style="width: 18rem;">
            <div class="d-flex justify-content-center">
                <p>Le <?=dates($date);?> - à <?=hour($hour);?></p>
            </div>
            <div class="d-flex justify-content-center">
                <img src="<?=$match['img'];?>" alt="logo domicile"width="50" height="50">
            </div>
            <div class="d-flex justify-content-center">
                <p><?=$match['title'];?></p>
            </div>
            <div class="d-flex justify-content-center gap-2">
                <input class="saisie" type="number"name="score_local" id="">
                <p> / </p>
                <input class="saisie" type="number" name="score_opponent" id="">
            </div>
            <div class="d-flex justify-content-center">
                <p><?=$match['opponent'];?></p>
            </div>
            <div class="d-flex justify-content-center">
                <img src="<?=$match['img_opponent'];?>" alt="logo adversaire"width="50" height="50">
            </div>
        </div>
    </div>
    <?php }?>
    <div class="container border border-2 rounded-2 p-3 ">
        <div>
            <h3 class="d-flex justify-content-center">Composition de l'équipe</h3>
        </div>
        <div class="gap-3 d-flex justify-content-center flex-wrap">
        <?php
            foreach($listeUsers as $user){
                foreach($user as $key => $value)
                $user[$key] = secure($value);
                ?>
            <div class="card " style="width: 18rem;">
            
                <div class="fw-bold d-flex justify-content-center card-header">
                    <?=$user['lastname'];?> . <?=$user['firstname'];?>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-center"><?=$user['job'];?></li>
                    <li class="list-group-item d-flex justify-content-evenly gap-2">
                        <div>
                            <img src="./assets/icon/timeplay.png" width="24" height="24" alt="temps de jeux"><input class="saisie" type="number" name="timeplay<?=$user['user_id'];?>" min="0" oninput="if(this.value < 0) this.value = 0;" id="">
                        </div>
                        <div>
                            <img src="./assets/icon/but.png" width="24" height="24" alt="but"><input class="saisie" type="number" name="goal<?=$user['user_id'];?>" min="0" oninput="if(this.value < 0) this.value = 0;" id="">
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-evenly gap-2">
                        <div>
                            <img src="./assets/icon/cards.png" width="24" height="24" alt=""><input class="saisie" type="number" name="cards<?=$user['user_id'];?>" min="0" oninput="if(this.value < 0) this.value = 0;" id="">
                        </div>
                        <div>
                            <img src="./assets/icon/pass.png" width="24" height="24" alt=""><input class="saisie" type="number" name="pass<?=$user['user_id'];?>" min="0" oninput="if(this.value < 0) this.value = 0;" id="">
                        </div>
                    </li>
                    <li class="list-group-item d-flex justify-content-center gap-2">
                        <div>
                            <img  src="./assets/icon/profil.png" width="24" height="24" alt=""><a class="lien" href="">Statistique</a>
                        </div>
                    </li>
                </ul>
            </div>
            <?php }?>
        </div>
        <div class="p-2">
            <label for="comment"class="form-label">Commentaire du match</label>
            <textarea  class="form-control d-inline-flex focus-ring focus-ring-dark py-1 px-2 text-decoration-none border 
            rounded" name="comment" id="comment" cols="20" rows="5"required></textarea>
        </div>
        <div class="col-4 p-2">
            <label for="file">Enregistrer la photo du match</label>
            <input class="px-2" type="file" name="file">
        </div>
    </div>
    <div class="p-2 d-flex justify-content-center">
                <a href="./report.php?id=<?=$match['match_id'];?>" name="save_report" type="submit">
                <input class="btn btn-outline-secondary" type="submit" name="save_report" id="" value="Valider la feuille de match"></a>
            </div>
    </form>
</section>    

<?php
    require_once('./lib/footer.php');
?>
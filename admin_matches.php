<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');
    $requete = $bdd->prepare("SELECT * FROM teams");
    $requete->execute();
    $listeTeams = $requete->fetchAll();
    $date = "";
?>
<section class="container-fluid">
    <div class="p-2 d-flex justify-content-start">
        <?php if (isset($_SESSION['selected_profil']) AND $_SESSION['selected_profil']!= 3){?> <a href="./admin_match.php" class="btn btn-outline-secondary" type="submit">Création d'un match</a><?php }?>
    </div>
    <div>
        <h3 class="d-flex justify-content-center">Liste des matchs</h3>
    </div>
    <div class="d-flex justify-content-center">
        <div class="p-2"style="width: 18rem;">
            <label for="team"class="form-label">Choix de l'équipe :</label>
            <select class="form-control d-inline-flex focus-ring focus-ring-dark py-1 px-2 text-decoration-none border rounded-2"
                name="team" id="team">
                <?php
                foreach($listeTeams as $team){
                    foreach($team as $key => $value)
                    $team[$key] = secure($value);
                    
                if(isset($team['team_id'])!=null and ($team['team_id']!="")){?>
                    <option class="teamid" id="teamid" value="<?=$team['team_id'];?>"><?=$team['title'];?></option>
                <?php }else{?>
                    <option value="0">Pas d'équipe</option>
                <?php }} ?>
            </select>
        </div>
    </div>
</section>

<section class="container">
    <div class="d-flex justify-content-center flex-wrap gap-3">
    <?php 
        $requete_matches = $bdd->prepare("SELECT match_id, date, hour, address, ground, opponent, img_opponent, T.team_id, T.title, T.img 
        FROM matches M 
        left join teams T on T.team_id = M.team_id");
        $requete_matches->execute();
        $listeMatches = $requete_matches->fetchAll();
        foreach($listeMatches as $matchs){
            foreach($matchs as $key => $value)
            $matchs[$key] = secure($value);
        $date = $matchs['date'];
        $hour = $matchs['hour'];
        ?>
        <a class="lien" href="./convocation.php?id=<?=$matchs['match_id'];?>">
        <div class="p-2 border rounded-2 match-card" data-team-id="<?=$matchs['team_id'];?>"style="width: 18rem;">
            <div class="d-flex justify-content-center">
                <p>Le <?=dates($date);?> - à <?=hour($hour);?></p>
            </div>
            <div class="d-flex justify-content-center">
                <img src="<?=$matchs['img'];?>" alt="logo domicile"width="50" height="50">
            </div>
            <div class="d-flex justify-content-center">
                <p><?=$matchs['title'];?></p>
            </div>
            <div class="d-flex justify-content-center">
                <p>-</p>
            </div>
            <div class="d-flex justify-content-center">
                <p><?=$matchs['opponent'];?></p>
            </div>
            <div class="d-flex justify-content-center">
                <img src="<?=$matchs['img_opponent'];?>" alt="logo adversaire"width="50" height="50">
            </div>
            </a>
            <ul class="list-group">
                <li class="list-group-item d-flex justify-content-around">
                    <a href="./convocation.php?id=<?=$matchs['match_id'];?>"class="btn btn-outline-secondary centre" type="submit">Convocation</a>
                    <a href="./admin_match_sheet.php?id=<?=$matchs['match_id'];?>"class="btn btn-outline-secondary centre" type="submit">Feuille du match</a>
                </li>
            </ul>
        </div>
        <?php }?>
    </div>
</section>

<?php
    require_once('./lib/footer.php');
?>
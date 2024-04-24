<?php
require_once('./lib/header.php');
require_once('./lib/config.php');
$userid = $_GET['id'];
$requete = $bdd->prepare("SELECT lastname, firstname, team_id, R.profil_id as role_id FROM users U 
left join roles R on R.user_id = U.user_id 
left join teams_users TU ON TU.user_id = U.user_id
WHERE U.user_id = :user_id");
$requete->execute(
    array(
        "user_id" => $userid
    ));
$recup_userAll = $requete->fetchAll();
foreach($recup_userAll as $user){
    foreach($user as $key => $value)
    $user[$key] = secure($value);
}
$role_id = $user['role_id'];
$team_id = $user['team_id'];
$requete = $bdd->prepare("SELECT * FROM profils");
$requete->execute();
$listeProfils = $requete->fetchAll();
$requete_teams = $bdd->prepare("SELECT * FROM teams");
$requete_teams->execute();
$listeTeams = $requete_teams->fetchAll();

?>
<section class="container">
<div class="m-5 border border-2 rounded-3">
        <form method="post" action="">
        <input type="hidden" name="old_team" value="<?=$user['team_id'];?>">
        <input type="hidden" name="token" value="<?=htmlspecialchars($_SESSION['token']);?>">
            <h1 class="p-2 text-center">Définir une équipe</h1>
            <div class="p-3 d-flex justify-content-center gap-5">
                <label class="p-2 border border-3 rounded-3"><?=$user['lastname'];?></label>
                <label class="p-2 border border-3 rounded-3"><?=$user['firstname'];?></label>
            </div>
            <div class="p-2 d-flex justify-content-center">
                <div>
                    <select class="box_select" name="team" id="team">
                        <?php foreach($listeTeams as $teams){
                                foreach($teams as $key => $value)
                                $teams[$key] = secure($value);?>
                        <option value="<?=$teams['team_id'];?>"<?php if($teams['team_id']==$team_id){?> selected <?php } ?>><?=$teams['title'];?></option>
                    <?php }?>
                    </select>
                </div>
            </div>
            <div class="p-4 d-flex justify-content-center gap-5">
                <a href="<?php if($_SESSION['selected_profil'] == 1){?>./admin_users.php<?php }else{?>./admin_player.php<?php }?>" class="btn btn-danger" type="submit">Fermer</a>
                <button type="submit" name="save_team" class="btn btn-secondary">Sauvegarde</button>
            </div>
        </form>
    </div>

<?php if(($_SESSION['selected_profil']) == 1){ ?>
    <div class="m-5 border border-2 rounded-3">
        <form method="post" action="">
        <input type="hidden" name="old_profile" value="<?=$role_id;?>">
        <input type="hidden" name="token" value="<?=htmlspecialchars($_SESSION['token']);?>">
            <h1 class="p-2 text-center">Définir un rôle</h1>
            <div class="p-3 d-flex justify-content-center gap-5">
                <label class="p-2 border border-3 rounded-3"><?=$user['lastname'];?></label>
                <label class="p-2 border border-3 rounded-3"><?=$user['firstname'];?></label>
            </div>
            <div class="p-2 d-flex justify-content-center">
                <div>
                    <select class="box_select" name="role" id="role">
                        <?php foreach($listeProfils as $profil){
                                foreach($profil as $key => $value)
                                $profil[$key] = secure($value);?>
                        <option value="<?=$profil['profil_id'];?>"<?php if($profil['profil_id']==$role_id){?> selected <?php }?>><?=$profil['profil_name'];?></option>
                    <?php }?>
                    </select>
                </div>
            </div>
            <div class="p-4 d-flex justify-content-center gap-5">
                <a href="./admin_users.php" class="btn btn-danger" type="submit">Fermer</a>
                <button type="submit" name="save_role" class="btn btn-secondary">Sauvegarde</button>
            </div>
        </form>
    </div>
    <?php }else{?>

    <?php }?>
</section>
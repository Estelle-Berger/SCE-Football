<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');
    $userid = $_GET['id'];
    $requete = $bdd->prepare("SELECT U.lastname, U.firstname, U.statut, U.email, job
            FROM users U 
            left join postes P on P.Poste_id = U.job WHERE user_id = :user_id");
    $requete->execute(
        array(
            "user_id" => $userid
        )
    );
    $recup_userAll = $requete->fetchAll();
    foreach($recup_userAll as $user){
        foreach($user as $key => $value)
        $user[$key] = secure($value);
    }
    $requete_poste = $bdd->prepare("SELECT * FROM postes ");
    $requete_poste->execute();
    $listesPostes = $requete_poste->fetchAll();
?>
<section class="p-5 container-fluid">
    <form method="POST" action="">
        <div class="p-3 border border-3 rounded-3">
        <h3 class="fx-bold text-center">Modification d'utilisateur</h3>
            <input type="hidden" name="token" value="<?=htmlspecialchars($_SESSION['token']);?>">
            <div class="row">
                <div class="col-6 p-2">
                    <label for="lastname" class="form-label">Nom*</label>
                    <input type="text"class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                    value="<?=$user['lastname'];?>" id="lastname" name="lastname" required>
                </div>
                <div class="col-6 p-2">
                    <label for="firstname" class="form-label">Prénom*</label>
                    <input type="text"class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                    value="<?=$user['firstname'];?>" id="firstname" name="firstname" required>
                </div>
            </div>
            <div class="row">
                <div class="col-6 p-2">
                    <label for="job" class="form-label">Poste</label>
                    <select class="form-control d-inline-flex focus-ring focus-ring-dark py-1 px-2 text-decoration-none border rounded" 
                    aria-placeholder="" name="job" id="job">
                    <?php foreach($listesPostes as $poste){
                        foreach($poste as $key => $value)
                        $poste[$key] = secure($value);?>
                        <option value="<?=$poste['Poste_id'];?>" <?php if($poste['Poste_id']==$user['job']){?> selected <?php } ?>><?=$poste['PosteName'];?></option>
                        <?php } ?>
                    </select>
                </div>
                <div class="col-6 p-2">
                    <label for="email"class="form-label">Email*</label>
                    <input type="email"class="form-control d-inline-flex focus-ring focus-ring-dark py-1 px-2 text-decoration-none border rounded-2"
                    value="<?=$user['email'];?>" id="email" name="email" required>
                </div>
            </div>
            <div class="p-2 d-flex justify-content-center">
                <div class="btn-group" role="group" aria-label="Basic radio toggle button group">
                    <input type="radio" class="btn-check" name="statut" id="btnradio1" value="1" autocomplete="off"<?php if($user['statut'] == '1'){?> checked <?php } ?>>
                    <label class="btn btn-outline-danger" for="btnradio1">Statut actif</label>

                    <input type="radio" class="btn-check" name="statut" id="btnradio2" value="0" autocomplete="off"<?php if($user['statut'] == '0'){?> checked <?php } ?>>
                    <label class="btn btn-outline-danger" for="btnradio2">Statut inactif</label>
                </div>
            </div>
        </div>
        <div class="p-2 d-flex justify-content-center">
            <input class="btn btn-outline-secondary" type="submit" id="mybutton" name="update_user" value="Enregistrer">
        </div>
        <div class="p-2 d-flex justify-content-center">
            <a href="<?php if ($_SESSION['profils'] == 1){?>./admin_users.php<?php }else{?>./admin_player.php"<?php }?> class="btn btn-outline-secondary" type="submit">Liste utilisateurs</a>
        </div>
    </form>
</section>
<?php
    require_once('./lib/footer.php');
?>
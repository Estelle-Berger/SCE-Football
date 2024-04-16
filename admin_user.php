<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');
    $requete_poste = $bdd->prepare("SELECT * FROM postes");
    $requete_poste->execute();
    $listesPostes = $requete_poste->fetchAll();
    
?>
<section class="p-5 container-fluid">
    <form method="POST" action="">
        <div class="p-3 border border-3 rounded-3">
        <h3 class="fx-bold text-center">Création d'utilisateur</h3>
            <input type="hidden" name="token" value="<?=htmlspecialchars($_SESSION['token']);?>">
            <div class="row">
                <div class="col-5 p-2">
                    <label for="lastname" class="form-label">Nom*</label>
                    <input type="text"class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                    placeholder="Nom" id="lastname" name="lastname" required>
                </div>
                <div class="col-5 p-2">
                    <label for="firstname" class="form-label">Prénom*</label>
                    <input type="text"class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                    placeholder="Prénom" id="firstname" name="firstname" required>
                </div>
                <div class="col-2 p-2">
                    <label for="date_birth" class="form-label">Date de naissance*</label>
                    <input type="date" class="form-control d-inline-flex focus-ring focus-ring-dark py-1 px-2 text-decoration-none border 
                    rounded" name="date_birth" id="date_birth"required>
                </div>
            </div>
            <div class="row">
                <div class="col-6 p-2">
                    <label for="job" class="form-label">Poste</label>
                    <select class="form-control d-inline-flex focus-ring focus-ring-dark py-1 px-2 text-decoration-none border rounded"
                    name="job" id="job">
                    <?php foreach($listesPostes as $poste){
                        foreach($poste as $key => $value)
                        $poste[$key] = secure($value);?>
                        <option value="<?=$poste['Poste_id'];?>"><?=$poste['PosteName'];?></option>
                        <?php } ?>
                </select>
                </div>
                <div class="col-6 p-2">
                    <label for="email"class="form-label">Email*</label>
                    <input type="email"class="form-control d-inline-flex focus-ring focus-ring-dark py-1 px-2 text-decoration-none border rounded-2"
                    placeholder="email" id="email" name="email" required>
                </div>
            </div>
        </div>
        <div class="p-2 d-flex justify-content-center">
            <input class="btn btn-outline-secondary" type="submit" id="mybutton" name="save_user" value="Enregistrer">
        </div>
        <div class="p-2 d-flex justify-content-center">
            <a href="<?php if ($_SESSION['profils'] == 1){?>./admin_users.php<?php }else{?>./admin_player.php"<?php }?> class="btn btn-outline-secondary" type="submit">Liste utilisateurs</a>
        </div>
    </form>
</section>
<?php
    require_once('./lib/footer.php');
?>
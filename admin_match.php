<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');
    $requete = $bdd->prepare("SELECT * FROM teams");
    $requete->execute();
    $teams = $requete->fetchAll();
    
?>
<section class="p-5 container-fluid">
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="p-3 border border-3 rounded-3">
        <h3 class="fx-bold text-center">Création d'un match</h3>
            <input type="hidden" name="token" value="<?=htmlspecialchars($_SESSION['token']);?>">
            <div class="row">
                <div class="col-4 p-2">
                    <label for="opponent" class="form-label">Adversaire*</label>
                    <input type="text"class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                    placeholder="Adversaire" id="opponent" name="opponent" required>
                </div>
                <div class="col-4 p-2">
                    <label for="file">Choix du logo (max 5 Mo)</label>
                    <input class="px-2" type="file" name="file">
                </div>
                <div class="col-4 p-2">
                    <label for="team" class="form-label">Equipe local*</label>
                    <select class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                    id="team" name="team">
                    <?php foreach($teams as $team){
                        foreach($team as $key => $value)
                        $team[$key] = secure($value);?>
                    <option value="<?=$team['team_id'];?>"><?=$team['title'];?></option>
                    <?php }?>
                </select>
                </div>
            </div>
            <div class="row">
                <div class="col-4 p-2">
                    <label for="address" class="form-label">Adresse*</label>
                    <textarea  class="form-control d-inline-flex focus-ring focus-ring-dark py-1 px-2 text-decoration-none border 
                    rounded" placeholder="N°, rue, code postale et ville" name="address" id="address" cols="15" rows="3"required></textarea>
                </div>
                <div class="col-4 p-2">
                    <label for="ground" class="form-label">Terrain*</label>
                    <input type="text"class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                    placeholder="Terrain" id="ground" name="ground" required>
                </div>
                <div class="col-2 p-2">
                    <label for="date" class="form-label">Date*</label>
                    <input type="date"class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                    placeholder="" id="date" name="date" required>
                </div>
                <div class="col-1 p-2">
                    <label for="hour" class="form-label">Heure*</label>
                    <input type="time"class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                    placeholder="" id="hour" name="hour" required>
                </div>
            </div>
        </div>
        <div class="p-2 d-flex justify-content-center">
            <input class="btn btn-outline-secondary" type="submit" id="mybutton" name="save_matches" value="Enregistrer">
        </div>
        <div class="p-2 d-flex justify-content-center">
            <a href="./admin_matches.php" class="btn btn-outline-secondary" type="submit">Liste des matchs</a>
        </div>
    </form>
</section>
<?php
    require_once('./lib/footer.php');
?>
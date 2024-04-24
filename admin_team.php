<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');
//----------récupération des données------------
    $requete = $bdd->prepare("SELECT U.user_id, lastname, firstname, R.profil_id FROM users U left join roles R ON R.user_id = U.user_id where R.profil_id = 2");
    $requete->execute();
    $listeTeams = $requete->fetchAll();
    
?>
<section class="p-3 container-fluid">
    <form method="POST" action="" enctype="multipart/form-data">
        <div class="p-3 border rounded-3">
        <h3 class="fx-bold">Création d'équipes</h3>
            <input type="hidden" name="token" value="<?=htmlspecialchars($_SESSION['token']);?>">
                <div class="p-2">
                    <label for="title" class="form-label">Nom de l'équipe</label>
                    <input type="text"class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                    placeholder="Nom de l'équipe" id="title" name="title" required>
                </div>    
                <div class="p-2">
                    <label for="name" class="form-label">Nom du coach</label>
                    <select class="form-control d-inline-flex focus-ring focus-ring-dark py-1 px-2 text-decoration-none border rounded"
                    name="name<?=$team['user_id'];?>" id="name">
                    <?php foreach($listeTeams as $team){
                        foreach($team as $key => $value)
                        $team[$key] = secure($value);?>
                        <option value="<?=$team['lastname'];?> . <?=$team['firstname'];?>"><?=$team['lastname'];?> . <?=$team['firstname'];?></option>
                        <?php }?>
                </select>
                </div>
                <div class="p-2">
                    <label for="description"class="form-label">Description de l'équipe</label>
                    <textarea  class="form-control d-inline-flex focus-ring focus-ring-dark py-1 px-2 text-decoration-none border 
                    rounded" name="comment" id="comment" cols="20" rows="5"required></textarea>
                </div>
            </div>
            <div class="p-2">
                <label for="file">Choix du logo (max 5 Mo)</label>
                <input class="px-2" type="file" name="file">
            </div>
            <div class="p-2 d-flex justify-content-center">
                <input class="btn btn-outline-secondary" type="submit" id="mybutton" name="save_teams" value="Envoyer">
            </div>
            <div class="p-2 d-flex justify-content-center">
            <a href="./admin_teams.php" class="btn btn-outline-secondary" type="submit">Liste des équipes</a>
        </div>
    </form>
</section>
<?php
    require_once('./lib/footer.php');
?>
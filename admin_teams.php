<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');
//----------récupération des données------------
    $requete = $bdd->prepare("SELECT*FROM teams");
    $requete->execute();
    $listeTeams = $requete->fetchAll();
    foreach($listeTeams as $team){
        foreach($team as $key => $value)
        $team[$key]= secure($value);}
?>
<section class="container-fluid">
    <h3 class="d-flex justify-content-center">Equipes</h3>
<div class="d-flex justify-content-center flex-wrap">
    <div class="card" style="width: 18rem;">
        <img src="<?=$team['img'];?>" class="card-img-top" alt="<?=$team['title'];?>">
        <div class="card-body">
            <p class="card-text"><?=$team['comment'];?></p>
        </div>
        <ul class="list-group list-group-flush">
            <li class="list-group-item">Nom :<?=$team['title'];?></li>
            <li class="list-group-item">Coach :<?=$team['name'];?></li>
        </ul>
        <div class="card-body">
            <a href="./lib/delete_team.php?id=<?php ?>" class="card-link lien">Supprimer</a>
            <a href="#" class="card-link lien">Voir l'équipe</a>
        </div>
    </div>
</div>
    
</section>
<section class="p-3 container-fluid">
    <form method="POST" action="">
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
                    <input type="text"class="form-control d-inline-flex focus-ring focus-ring-dark text-decoration-none border rounded-2"
                    placeholder="Nom du coach" id="name" name="name" required>
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
        
    </form>
</section>
<?php
    require_once('./lib/footer.php');
?>
<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');
//----------récupération des données------------
    $requete = $bdd->prepare("SELECT*FROM teams");
    $requete->execute();
    $listeTeams = $requete->fetchAll();
    
?>
<div class="p-2 d-flex justify-content-start">
<?php if ($_SESSION['selected_profil']==1){?><a href="./admin_team.php" class="btn btn-outline-secondary" type="submit">Création d'équipe</a><?php }?>
    
</div>
<section class="p-2 container-fluid">
    <h1 class="p-2 d-flex justify-content-center">Equipes</h1>
<div class="d-flex justify-content-center flex-wrap">
    <?php foreach($listeTeams as $team){
        foreach($team as $key => $value)
        $team[$key]= secure($value);
    ?>
    
        <div class="p-2">
        <div class="card" style="width: 18rem;">
            <img src="<?=$team['img'];?>" class="card-img-top" alt="<?=$team['title'];?>">
            <div class="card-body">
                <p class="card-text"><?=$team['comment'];?></p>
            </div>
            <ul class="list-group list-group-flush">
                <li class="list-group-item">Nom : <?=$team['title'];?></li>
                <li class="list-group-item">Coach : <?=$team['name'];?></li>
            </ul>
            <div class="card-body d-flex justify-content-center">
                <a href="./teams.php?id=<?=$team['team_id'];?>" class="card-link lien">Voir l'équipe</a>
            <?php if(isset($_SESSION['isLogged']) AND $_SESSION['isLogged']==true){
                    if(isset($_SESSION['selected_profil']) AND $_SESSION['selected_profil']== 1){?>
                <a href="./lib/delete_team.php?id=<?=$team['team_id'];?>"class="card-link lien">Supprimer</a>
                <?php }}?>
            </div>
            
        </div>
    </div>
    <?php } ?>
</div>

<?php
    require_once('./lib/footer.php');
?>
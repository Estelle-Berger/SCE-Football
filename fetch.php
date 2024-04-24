<?php 
require_once('./lib/config.php');

$requete_matches = $bdd->prepare("SELECT match_id, date, hour, opponent, img_opponent, T.team_id, T.title, T.img 
FROM matches M 
left join teams T on T.team_id = M.team_id");
$requete_matches->execute();
$listeMatches = $requete_matches->fetchAll();

foreach($listeMatches as $matchs){
    foreach($matchs as $key => $value)
    $matchs[$key] = secure($value);
$returndata="";
$date = $matchs['date'];
$hour = $matchs['hour'];

$returndata = $returndata.'
<section class="container">
    <div class="d-flex justify-content-center flex-wrap gap-3">
        <a class="lien" href="./admin_match_sheet.php?id='.$matchs['match_id'].'">
        <div class="border rounded-2" style="width: 18rem;">
            <div class="d-flex justify-content-center">
                <p>Le '.dates($date).'. - à '.hour($hour).'.</p>
            </div>
            <div class="d-flex justify-content-center">
                <img src="'.$matchs['img'].'" alt="logo domicile"width="50" height="50">
            </div>
            <div class="d-flex justify-content-center">
                <p>'.$matchs['title'].'</p>
            </div>
            <div class="d-flex justify-content-center">
                <p>le score</p>
            </div>
            <div class="d-flex justify-content-center">
                <p>'.$matchs['opponent'].'</p>
            </div>
            <div class="d-flex justify-content-center">
                <img src="'.$matchs['img_opponent'].'" alt="logo adversaire"width="50" height="50">
            </div>
        </div>
        </a>
    </div>
</section>';
}
echo $returndata;
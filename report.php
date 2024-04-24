<?php
    require_once('./lib/header.php');
    require_once('./lib/config.php');
    $id =$_GET['id'];
    $requete_report = $bdd->prepare("SELECT DISTINCT timeplay, goal, cards, pass, img_match, M.comment, M.date, M.hour, M.address, M.ground,
    M.score_local, M.score_opponent, M.opponent, M.img_opponent, T.title, T.img, U.lastname, U.firstname
    FROM reports R
    left join matches M on M.match_id = R.match_id
    left join teams T on T.team_id = M.team_id
    left join users U on U.user_id = R.player
    where R.match_id = :id");
    $requete_report->execute(
        array(
            "id"=>$id
        )
    );
    $listeReports = $requete_report->fetchAll();
    if ($requete_report->rowCount()>=1){

        $date = $listeReports[0]['date'];
        $hour = $listeReports[0]['hour'];
        $title = $listeReports[0]['title'];
        $score_local = $listeReports[0]['score_local'];
        $score_opponent = $listeReports[0]['score_opponent'];
        $img_opponent = $listeReports[0]['img_opponent'];
        $opponent = $listeReports[0]['opponent'];
        $comment = $listeReports[0]['comment'];
        $img_match = $listeReports[0]['img_match'];
        $address = $listeReports[0]['address'];
        $ground = $listeReports[0]['ground'];

    ?>
    <div>
        <div class="p-2 d-flex justify-content-center">
            <h3>Le <?=dates($date);?> - à <?=hour($hour);?></h3>
        </div>
        <div class="p-2 d-flex justify-content-center">
            <h5>A <?=$address?> sur le terrain du <?=$ground?></h5>
        </div>
    </div>
    <section class="container border">
        <div class="p-3 row d-flex justify-content-center flex_wrap gap-2">
            <div class="col-4 row">
                <div class="p-2 d-flex justify-content-center" >
                    <img src="./assets/icon/test_logo_SCE.png" alt="local" width="50" height="50">
                </div>
                <p class="p-2 d-flex justify-content-center"><?=$title?></p>
            </div>
            <div class="p-2 col-1 d-flex justify-content-center align-items-center">
                <h2><?=$score_local?></h2>
            </div>
            <div class="p-2 col-1 d-flex justify-content-center align-items-center">
                <p class="fw-bold">_</p>
            </div>
            <div class="p-2 col-1 d-flex justify-content-center align-items-center">
                <h2><?=$score_opponent?></h2>
            </div>
            <div class="col-4 row">
                <div class="p-2 d-flex justify-content-center">
                    <img src="<?=$img_opponent?>" alt="adv" width="50" height="50">
                </div>
                <p class="p-2 d-flex justify-content-center"><?=$opponent?></p>
            </div>
        </div>
    </section>
    <section class="p-2 container">
        <div class="row d-flex justify-content-center gap-2 ">
            <?php foreach($listeReports as $report){
            foreach($report as $key => $value)
                $report[$key] = secure($value); ?>
            <div class="card" style="width: 30rem;">
            <ul class="list-group list-group-flush">
                <li class="list-group-item d-flex justify-content-evenly">
                    <div class="col-4 row border d-flex justify-content-center align-items-center flex_wrap">
                        <p class="d-flex justify-content-center"><?=$report['lastname'];?></p>
                        <p class="d-flex justify-content-center"><?=$report['firstname'];?></p>
                    </div>
                    <div class="col-2 row border d-flex justify-content-center align-items-center flex_wrap">
                        <div class="d-flex justify-content-center" >
                            <img src="./assets/icon/timeplay.png" alt="tps game" width="24" height="24">
                        </div>
                        <p class="d-flex justify-content-center"><?=$report['timeplay'];?>'</p>
                    </div>
                    <div class="col-2 row border d-flex justify-content-center align-items-center flex_wrap">
                        <div class="d-flex justify-content-center" >
                            <img src="./assets/icon/pass.png" alt="pass" width="24" height="24">
                        </div>
                        <p class="d-flex justify-content-center"><?=$report['pass'];?></p>
                    </div>
                    <div class="col-2 row border d-flex justify-content-center align-items-center flex_wrap">
                        <div class="d-flex justify-content-center" >
                            <img src="./assets/icon/cards.png" alt="cards" width="24" height="24">
                        </div>
                        <p class="d-flex justify-content-center"><?=$report['cards'];?></p>
                    </div>
                    <div class="col-2 row d-flex border align-items-center flex_wrap">
                        <div class="d-flex justify-content-center" >
                            <img src="./assets/icon/but.png" alt="but" width="24" height="24">
                        </div>
                        <p class="d-flex justify-content-center"><?=$report['goal'];?></p>
                    </div>
                </li>
                
            </ul>
            </div>
            <?php }?>
        </div>
        <div class="p-3">
            <p class="p-2 d-flex justify-content-center border rounded-2"><?=$comment?></p>
        </div>
        <div class="p-3 d-flex justify-content-center">
            <img class="border rounded-2" src="<?=$img_match?>" alt="img du match"  width="50%" height="50%">
        </div>
        <?php }
        else {?>
        <div class="p-2 d-flex justify-content-center">
            <p class="border rounded p-2"> Match pas encore jouer <br>Résultat à venir </p>
        </div>
</section>

<?php
        }
    require_once('./lib/footer.php');
?>
